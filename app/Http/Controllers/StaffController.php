<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Показать форму регистрации по токену
     */
    public function showRegistrationForm(Request $request, $token)
    {
        // Проверяем, существует ли токен
        $staff = Staff::where('registration_token', $token)
                     ->where('registered_at', null)
                     ->first();

        if (!$staff) {
            return redirect()->route('registration.expired')->with('error', 'Ссылка для регистрации недействительна или уже использована.');
        }

        $shifts = Shift::all();
        return view('staff.register', compact('staff', 'shifts', 'token'));
    }

    /**
     * Обработать регистрацию сотрудника
     */
    public function register(Request $request, $token)
    {
        // Найти сотрудника по токену
        $staff = Staff::where('registration_token', $token)
                     ->where('registered_at', null)
                     ->first();

        if (!$staff) {
            return redirect()->route('registration.expired')->with('error', 'Ссылка для регистрации недействительна.');
        }

        // Валидация данных
        $validator = Validator::make($request->all(), [
            'shift_id' => 'required|exists:shifts,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|min:6|confirmed',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => ['required', 'regex:/^\+7[0-9]{10}$/', Rule::unique('staff')->ignore($staff->id)],
            'email' => ['required', 'email', Rule::unique('staff')->ignore($staff->id)],
            'telegram' => 'nullable|string|max:255',
            'position' => ['required', Rule::in(Staff::getPositions())],
            'is_self_employed' => 'boolean',
            'passport_series' => 'nullable|string|size:4',
            'passport_number' => 'nullable|string|size:6',
            'passport_issued_date' => 'nullable|date',
            'passport_issued_by' => 'nullable|string|max:255',
            'passport_code' => 'nullable|string|size:7',
            'inn' => 'nullable|string|size:12',
            'snils' => 'nullable|string|size:11',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:20',
            'bank_bik' => 'nullable|string|size:9',
            'bank_correspondent_account' => 'nullable|string|max:20',
        ], [
            'shift_id.required' => 'Выберите смену',
            'shift_id.exists' => 'Выбранная смена не существует',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'phone.regex' => 'Телефон должен быть в формате +7ХХХХХХХХХХ',
            'phone.unique' => 'Этот номер телефона уже зарегистрирован',
            'email.unique' => 'Этот email уже зарегистрирован',
            'position.in' => 'Выберите должность из списка',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Обработка загрузки аватара
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Обновление данных сотрудника
        $staff->update([
            'avatar' => $avatarPath,
            'password' => $request->password, // setPasswordAttribute автоматически хеширует
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'telegram' => $request->telegram,
            'position' => $request->position,
            'is_self_employed' => $request->boolean('is_self_employed'),
            'passport_series' => $request->passport_series,
            'passport_number' => $request->passport_number,
            'passport_issued_date' => $request->passport_issued_date,
            'passport_issued_by' => $request->passport_issued_by,
            'passport_code' => $request->passport_code,
            'inn' => $request->inn,
            'snils' => $request->snils,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'bank_bik' => $request->bank_bik,
            'bank_correspondent_account' => $request->bank_correspondent_account,
        ]);
        
        // Привязываем к смене
        if ($request->shift_id) {
            $staff->shifts()->sync([$request->shift_id]);
        }

        // Завершить регистрацию
        $staff->completeRegistration();

        return redirect()->route('staff.register.success')->with('success', 'Регистрация успешно завершена!');
    }

    /**
     * Показать страницу успешной регистрации
     */
    public function registrationSuccess()
    {
        return view('staff.success');
    }

    /**
     * Показать страницу истекшей регистрации
     */
    public function registrationExpired()
    {
        return view('staff.expired');
    }

    /**
     * Показать форму входа в личный кабинет
     */
    public function showLoginForm()
    {
        return view('staff.login');
    }

    /**
     * Обработать вход в личный кабинет
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Попытка аутентификации через guard
        if (auth('staff')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $staff = auth('staff')->user();
            
            if (!$staff->isRegistered()) {
                auth('staff')->logout();
                return redirect()->back()
                               ->withErrors(['email' => 'Регистрация не завершена'])
                               ->withInput();
            }

            $request->session()->regenerate();
            return redirect()->intended(route('staff.dashboard'));
        }

        return redirect()->back()
                       ->withErrors(['email' => 'Неверный email или пароль'])
                       ->withInput();
    }

    /**
     * Показать личный кабинет сотрудника
     */
    public function dashboard()
    {
        $staff = auth('staff')->user();
        return view('staff.dashboard', compact('staff'));
    }

    /**
     * Выйти из личного кабинета
     */
    public function logout(Request $request)
    {
        auth('staff')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Вы успешно вышли из системы');
    }

    /**
     * Show self-registration form
     */
    public function showSelfRegistrationForm()
    {
        $shifts = Shift::where('start_date', '>', now())
            ->orderBy('start_date')
            ->get();
        
        return view('staff.self-register', compact('shifts'));
    }

    /**
     * Handle self-registration
     */
    public function selfRegister(Request $request)
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'avatar' => 'nullable|image|max:2048',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:staff,email',
            'telegram' => 'nullable|string|max:255',
            'position' => 'required|in:вожатый,администратор,спорт инструктор,медработник,диджей,фотограф,кухонный работник,другое',
            'is_self_employed' => 'boolean',
            'passport_series' => 'required|string|max:10',
            'passport_number' => 'required|string|max:10',
            'passport_issued_date' => 'required|date',
            'passport_issued_by' => 'required|string|max:255',
            'passport_code' => 'required|string|max:10',
            'inn' => 'required|string|max:20',
            'snils' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'bank_account' => 'required|string|max:30',
            'bank_bik' => 'required|string|max:20',
            'bank_correspondent_account' => 'required|string|max:30',
        ]);

        // Извлекаем shift_id отдельно, так как он не должен быть в fillable
        $shiftId = $validated['shift_id'];
        unset($validated['shift_id']);
        
        $staff = new Staff($validated);
        
        // Обработка загрузки аватара
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $staff->avatar = $path;
        }

        // Хеширование пароля (setPasswordAttribute автоматически хеширует)
        $staff->password = $validated['password'];
        
        // Генерация токена регистрации
        $staff->registration_token = Staff::generateRegistrationToken();
        
        // Установка даты регистрации
        $staff->registered_at = now();
        
        // Сохранение
        $staff->save();
        
        // Привязываем к смене
        $staff->shifts()->attach($shiftId);

        // Автоматический вход
        auth('staff')->login($staff);

        return redirect()->route('staff.dashboard')
            ->with('success', 'Регистрация прошла успешно!');
    }
    
    /**
     * Показать список отрядов, где сотрудник является вожатым
     */
    public function squads()
    {
        $staff = auth('staff')->user();
        
        // Проверяем, что сотрудник является вожатым
        if ($staff->position !== 'вожатый') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Доступ разрешен только вожатым');
        }
        
        // Получаем отряды, где этот сотрудник является вожатым
        $squads = $staff->squads()->with(['shift', 'children'])->get();
        
        return view('staff.squads.index', compact('squads', 'staff'));
    }
    
    /**
     * Показать детали отряда
     */
    public function squadShow($squadId)
    {
        $staff = auth('staff')->user();
        
        // Проверяем, что сотрудник является вожатым
        if ($staff->position !== 'вожатый') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Доступ разрешен только вожатым');
        }
        
        // Получаем отряд и проверяем, что этот сотрудник является его вожатым
        $squad = $staff->squads()
            ->with(['shift', 'children'])
            ->findOrFail($squadId);
        
        return view('staff.squads.show', compact('squad', 'staff'));
    }
}
