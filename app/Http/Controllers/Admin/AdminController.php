<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Shift;
use App\Models\Squad;
use App\Models\Child;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Показать главную страницу админки
     */
    public function dashboard()
    {
        $totalStaff = Staff::count();
        $registeredStaff = Staff::whereNotNull('registered_at')->count();
        $pendingStaff = Staff::whereNull('registered_at')->count();
        $totalShifts = Shift::count();
        $totalSquads = Squad::count();
        $squadsWithCounselors = Squad::whereNotNull('counselor_id')->count();
        $totalChildren = Child::count();
        $childrenInSquads = Child::whereHas('squads')->count();

        return view('admin.dashboard', compact(
            'totalStaff',
            'registeredStaff', 
            'pendingStaff',
            'totalShifts',
            'totalSquads',
            'squadsWithCounselors',
            'totalChildren',
            'childrenInSquads'
        ));
    }

    /**
     * Показать список всех сотрудников
     */
    public function staffIndex(Request $request)
    {
        $query = Staff::with('shifts');

        // Фильтры
        if ($request->filled('shift_id')) {
            $query->whereHas('shifts', function($q) use ($request) {
                $q->where('shifts.id', $request->shift_id);
            });
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('status')) {
            if ($request->status === 'registered') {
                $query->whereNotNull('registered_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('registered_at');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $staff = $query->paginate(20);
        $shifts = Shift::all();
        $positions = Staff::getPositions();

        return view('admin.staff.index', compact('staff', 'shifts', 'positions'));
    }

    /**
     * Показать форму создания нового сотрудника
     */
    public function staffCreate()
    {
        $shifts = Shift::all();
        return view('admin.staff.create', compact('shifts'));
    }

    /**
     * Создать нового сотрудника и отправить ссылку для регистрации
     */
    public function staffStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:staff,email',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'shifts' => 'nullable|array',
            'shifts.*' => 'exists:shifts,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $token = Staff::generateRegistrationToken();

        $staff = Staff::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make('temp_password'), // Временный пароль
            'registration_token' => $token,
        ]);
        
        // Привязываем к сменам, если указаны
        if ($request->has('shifts') && is_array($request->shifts)) {
            $staff->shifts()->attach($request->shifts);
        }

        $registrationUrl = route('staff.register', ['token' => $token]);

        return redirect()->route('admin.staff.show', $staff)
                        ->with('success', 'Сотрудник создан. Ссылка для регистрации: ' . $registrationUrl);
    }

    /**
     * Показать детали сотрудника
     */
    public function staffShow(Staff $staff)
    {
        $staff->load('shifts');
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Показать форму редактирования сотрудника
     */
    public function staffEdit(Staff $staff)
    {
        $shifts = Shift::all();
        return view('admin.staff.edit', compact('staff', 'shifts'));
    }

    /**
     * Обновить данные сотрудника
     */
    public function staffUpdate(Request $request, Staff $staff)
    {
        $validator = Validator::make($request->all(), [
            'shifts' => 'nullable|array',
            'shifts.*' => 'exists:shifts,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => ['nullable', 'regex:/^\+7[0-9]{10}$/', Rule::unique('staff')->ignore($staff->id)],
            'email' => ['required', 'email', Rule::unique('staff')->ignore($staff->id)],
            'telegram' => 'nullable|string|max:255',
            'position' => ['nullable', Rule::in(Staff::getPositions())],
            'is_self_employed' => 'boolean',
            'rating' => 'nullable|integer|min:1|max:10',
            'admin_comment' => 'nullable|string',
            // Паспортные данные
            'passport_series' => 'nullable|string|size:4',
            'passport_number' => 'nullable|string|size:6',
            'passport_issued_date' => 'nullable|date',
            'passport_issued_by' => 'nullable|string|max:255',
            'passport_code' => 'nullable|string|size:7',
            'inn' => 'nullable|string|size:12',
            'snils' => 'nullable|string|size:11',
            // Банковские реквизиты
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:20',
            'bank_bik' => 'nullable|string|size:9',
            'bank_correspondent_account' => 'nullable|string|max:20',
        ], [
            'shift_id.exists' => 'Выбранная смена не существует',
            'first_name.required' => 'Имя обязательно для заполнения',
            'last_name.required' => 'Фамилия обязательна для заполнения',
            'phone.regex' => 'Телефон должен быть в формате +7ХХХХХХХХХХ',
            'phone.unique' => 'Этот номер телефона уже используется другим сотрудником',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.unique' => 'Этот email уже используется другим сотрудником',
            'position.in' => 'Выберите должность из списка',
            'rating.min' => 'Рейтинг должен быть от 1 до 10',
            'rating.max' => 'Рейтинг должен быть от 1 до 10',
            'passport_series.size' => 'Серия паспорта должна содержать 4 цифры',
            'passport_number.size' => 'Номер паспорта должен содержать 6 цифр',
            'passport_code.size' => 'Код подразделения должен быть в формате XXX-XXX',
            'inn.size' => 'ИНН должен содержать 12 цифр',
            'snils.size' => 'СНИЛС должен содержать 11 цифр',
            'bank_bik.size' => 'БИК должен содержать 9 цифр',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $request->only([
            'first_name', 'last_name', 'middle_name', 'phone', 'email', 
            'telegram', 'position', 'is_self_employed', 'rating', 'admin_comment',
            'passport_series', 'passport_number', 'passport_issued_date', 
            'passport_issued_by', 'passport_code', 'inn', 'snils',
            'bank_name', 'bank_account', 'bank_bik', 'bank_correspondent_account'
        ]);

        // Обработка загрузки аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он есть
            if ($staff->avatar) {
                Storage::disk('public')->delete($staff->avatar);
            }
            
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $staff->update($data);
        
        // Обновляем смены
        if ($request->has('shifts')) {
            $staff->shifts()->sync($request->shifts);
        }

        return redirect()->route('admin.staff.show', $staff)
                        ->with('success', 'Данные сотрудника обновлены');
    }

    /**
     * Удалить сотрудника
     */
    public function staffDestroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')
                        ->with('success', 'Сотрудник удален');
    }

    /**
     * Сгенерировать новую ссылку для регистрации
     */
    public function regenerateRegistrationLink(Staff $staff)
    {
        if ($staff->isRegistered()) {
            return redirect()->back()
                           ->with('error', 'Сотрудник уже зарегистрирован');
        }

        $token = Staff::generateRegistrationToken();
        $staff->update(['registration_token' => $token]);

        $registrationUrl = route('staff.register', ['token' => $token]);

        return redirect()->back()
                        ->with('success', 'Новая ссылка для регистрации: ' . $registrationUrl);
    }

    // ===== МЕТОДЫ ДЛЯ УПРАВЛЕНИЯ ОТРЯДАМИ =====

    /**
     * Показать отряды конкретной смены
     */
    public function squadsIndex(Shift $shift)
    {
        $squads = $shift->squads()->with('counselor')->get();
        return view('admin.squads.index', compact('shift', 'squads'));
    }

    /**
     * Показать форму создания отряда
     */
    public function squadCreate(Shift $shift)
    {
        // Получаем сотрудников-вожатых, которые участвуют в этой смене
        $counselors = $shift->staff()
            ->where('position', 'вожатый')
            ->whereDoesntHave('squads', function($query) use ($shift) {
                $query->where('squads.shift_id', $shift->id);
            })
            ->get();
        
        $ageGroups = Squad::getAgeGroups();
        
        return view('admin.squads.create', compact('shift', 'counselors', 'ageGroups'));
    }

    /**
     * Создать новый отряд
     */
    public function squadStore(Request $request, Shift $shift)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'age_group' => 'nullable|string',
            'counselor_id' => 'nullable|exists:staff,id',
        ], [
            'name.required' => 'Название отряда обязательно для заполнения',
            'counselor_id.exists' => 'Выбранная вожатая не найдена',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Проверяем, что вожатая не назначена на другой отряд в этой смене
        if ($request->counselor_id) {
            $existingSquad = Squad::where('shift_id', $shift->id)
                                  ->where('counselor_id', $request->counselor_id)
                                  ->first();
            
            if ($existingSquad) {
                return redirect()->back()
                               ->withErrors(['counselor_id' => 'Эта вожатая уже назначена на отряд "' . $existingSquad->name . '"'])
                               ->withInput();
            }
        }

        $squad = Squad::create([
            'name' => $request->name,
            'description' => $request->description,
            'age_group' => $request->age_group,
            'shift_id' => $shift->id,
            'counselor_id' => $request->counselor_id,
        ]);

        return redirect()->route('admin.shifts.squads.show', [$shift, $squad])
                        ->with('success', 'Отряд "' . $squad->name . '" создан');
    }

    /**
     * Массовое создание отрядов
     */
    public function squadBulkCreate(Request $request, Shift $shift)
    {
        $validator = Validator::make($request->all(), [
            'count' => 'required|integer|min:1|max:20',
        ], [
            'count.required' => 'Укажите количество отрядов',
            'count.integer' => 'Количество должно быть числом',
            'count.min' => 'Минимальное количество отрядов: 1',
            'count.max' => 'Максимальное количество отрядов: 20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $count = (int) $request->count;
        $createdSquads = [];
        
        // Получаем все отряды для этой смены и фильтруем их в PHP (SQLite не поддерживает REGEXP)
        $existingSquads = Squad::where('shift_id', $shift->id)->get();
        
        $maxNumber = 0;
        foreach ($existingSquads as $squad) {
            if (preg_match('/^Отряд №(\d+)$/', $squad->name, $matches)) {
                $maxNumber = max($maxNumber, (int) $matches[1]);
            }
        }

        // Создаем отряды
        for ($i = 1; $i <= $count; $i++) {
            $squadNumber = $maxNumber + $i;
            $squadName = "Отряд №{$squadNumber}";
            
            $squad = Squad::create([
                'name' => $squadName,
                'shift_id' => $shift->id,
            ]);
            
            $createdSquads[] = $squad->name;
        }

        $message = 'Создано отрядов: ' . count($createdSquads) . ' (' . implode(', ', $createdSquads) . ')';
        
        return redirect()->route('admin.shifts.squads.index', $shift)
                        ->with('success', $message);
    }

    /**
     * Показать детали отряда
     */
    public function squadShow(Shift $shift, Squad $squad)
    {
        $squad->load(['counselor', 'children' => function($query) {
            $query->orderBy('full_name');
        }]);
        
        // Получаем детей, которых можно добавить в отряд (не состоят в нем)
        $availableChildren = Child::whereDoesntHave('squads', function($query) use ($squad) {
            $query->where('squad_id', $squad->id);
        })->orderBy('full_name')->get();
        
        return view('admin.squads.show', compact('shift', 'squad', 'availableChildren'));
    }

    /**
     * Показать форму редактирования отряда
     */
    public function squadEdit(Shift $shift, Squad $squad)
    {
        // Получаем доступных вожатых (включая текущую)
        $counselors = $shift->staff()
            ->where('position', 'вожатый')
            ->where(function($query) use ($squad) {
                $query->whereDoesntHave('squads', function($q) use ($squad) {
                    $q->where('squads.shift_id', $squad->shift_id);
                })->orWhere('staff.id', $squad->counselor_id);
            })
            ->get();
        
        $ageGroups = Squad::getAgeGroups();
        
        return view('admin.squads.edit', compact('shift', 'squad', 'counselors', 'ageGroups'));
    }

    /**
     * Обновить отряд
     */
    public function squadUpdate(Request $request, Shift $shift, Squad $squad)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'age_group' => 'nullable|string',
            'counselor_id' => 'nullable|exists:staff,id',
        ], [
            'name.required' => 'Название отряда обязательно для заполнения',
            'counselor_id.exists' => 'Выбранная вожатая не найдена',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Проверяем, что вожатая не назначена на другой отряд в этой смене (кроме текущего)
        if ($request->counselor_id && $request->counselor_id != $squad->counselor_id) {
            $existingSquad = Squad::where('shift_id', $shift->id)
                                  ->where('counselor_id', $request->counselor_id)
                                  ->where('id', '!=', $squad->id)
                                  ->first();
            
            if ($existingSquad) {
                return redirect()->back()
                               ->withErrors(['counselor_id' => 'Эта вожатая уже назначена на отряд "' . $existingSquad->name . '"'])
                               ->withInput();
            }
        }

        $squad->update([
            'name' => $request->name,
            'description' => $request->description,
            'age_group' => $request->age_group,
            'counselor_id' => $request->counselor_id,
        ]);

        return redirect()->route('admin.shifts.squads.show', [$shift, $squad])
                        ->with('success', 'Отряд "' . $squad->name . '" обновлен');
    }

    /**
     * Удалить отряд
     */
    public function squadDestroy(Shift $shift, Squad $squad)
    {
        $squadName = $squad->name;
        $squad->delete();
        
        return redirect()->route('admin.shifts.squads.index', $shift)
                        ->with('success', 'Отряд "' . $squadName . '" удален');
    }

    /**
     * Добавить существующего ребенка в отряд
     */
    public function squadAddChild(Request $request, Shift $shift, Squad $squad)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|exists:children,id',
        ], [
            'child_id.required' => 'Выберите ребенка',
            'child_id.exists' => 'Выбранный ребенок не найден',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $child = Child::find($request->child_id);

        // Проверяем, не состоит ли ребенок уже в этом отряде
        if ($squad->children()->where('child_id', $child->id)->exists()) {
            return redirect()->back()
                           ->withErrors(['child_id' => 'Ребенок уже состоит в этом отряде'])
                           ->withInput();
        }

        // Добавляем ребенка в отряд
        $squad->children()->attach($child->id);

        return redirect()->route('admin.shifts.squads.show', [$shift, $squad])
                        ->with('success', 'Ребенок "' . $child->full_name . '" добавлен в отряд');
    }

    /**
     * Удалить ребенка из отряда
     */
    public function squadRemoveChild(Request $request, Shift $shift, Squad $squad, Child $child)
    {
        // Проверяем, состоит ли ребенок в этом отряде
        if (!$squad->children()->where('child_id', $child->id)->exists()) {
            return redirect()->back()
                           ->withErrors(['error' => 'Ребенок не состоит в этом отряде']);
        }

        // Удаляем ребенка из отряда
        $squad->children()->detach($child->id);

        return redirect()->route('admin.shifts.squads.show', [$shift, $squad])
                        ->with('success', 'Ребенок "' . $child->full_name . '" исключен из отряда');
    }

    // ===== МЕТОДЫ ДЛЯ УПРАВЛЕНИЯ ДЕТЬМИ =====

    /**
     * Показать список всех детей
     */
    public function childrenIndex(Request $request)
    {
        $query = Child::with(['squads.shift']);

        // Фильтры
        if ($request->filled('squad_id')) {
            $query->whereHas('squads', function($q) use ($request) {
                $q->where('squad_id', $request->squad_id);
            });
        }

        if ($request->filled('shift_id')) {
            $query->whereHas('squads.shift', function($q) use ($request) {
                $q->where('id', $request->shift_id);
            });
        }

        if ($request->filled('age_from')) {
            $ageFrom = (int) $request->age_from;
            // Ребенку должно быть минимум $ageFrom лет
            // Значит он родился не позднее чем $ageFrom лет назад
            $dateFrom = now()->subYears($ageFrom);
            $query->where('birth_date', '<=', $dateFrom);
        }

        if ($request->filled('age_to')) {
            $ageTo = (int) $request->age_to;
            // Ребенку должно быть максимум $ageTo лет
            // Значит он родился не раньше чем ($ageTo + 1) лет назад
            $dateTo = now()->subYears($ageTo + 1);
            $query->where('birth_date', '>=', $dateTo);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('full_name', 'like', "%{$search}%");
        }

        // Клонируем запрос для статистики, чтобы не влиять на основной запрос
        $statsQuery = clone $query;
        
        // Получаем статистику для всех детей с учетом фильтров
        $totalChildren = $statsQuery->count();
        $childrenInSquads = $statsQuery->whereHas('squads')->count();
        $childrenWithoutSquads = $totalChildren - $childrenInSquads;

        $children = $query->orderBy('full_name')->paginate(20);
        $squads = Squad::with('shift')->get();
        $shifts = Shift::all();

        return view('admin.children.index', compact(
            'children', 
            'squads', 
            'shifts',
            'totalChildren',
            'childrenInSquads',
            'childrenWithoutSquads'
        ));
    }

    /**
     * Показать форму создания ребенка
     */
    public function childCreate()
    {
        $squads = Squad::with('shift')->get();
        return view('admin.children.create', compact('squads'));
    }

    /**
     * Создать нового ребенка
     */
    public function childStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'squads' => 'nullable|array',
            'squads.*' => 'exists:squads,id',
            'notes' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'full_name.required' => 'ФИО ребенка обязательно для заполнения',
            'birth_date.required' => 'Дата рождения обязательна для заполнения',
            'birth_date.before' => 'Дата рождения должна быть в прошлом',
            'squads.*.exists' => 'Один из выбранных отрядов не существует',
            'avatar.image' => 'Файл должен быть изображением',
            'avatar.mimes' => 'Поддерживаемые форматы: JPEG, PNG, JPG, GIF',
            'avatar.max' => 'Размер изображения не должен превышать 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars/children', 'public');
        }

        $child = Child::create([
            'full_name' => $request->full_name,
            'birth_date' => $request->birth_date,
            'notes' => $request->notes,
            'avatar' => $avatarPath,
        ]);

        // Привязываем к отрядам
        if ($request->squads) {
            $child->squads()->attach($request->squads);
        }

        return redirect()->route('admin.children.show', $child)
                        ->with('success', 'Ребенок "' . $child->full_name . '" добавлен');
    }

    /**
     * Показать детали ребенка
     */
    public function childShow(Child $child)
    {
        $child->load(['squads.shift', 'squads.counselor']);
        return view('admin.children.show', compact('child'));
    }

    /**
     * Показать форму редактирования ребенка
     */
    public function childEdit(Child $child)
    {
        $squads = Squad::with('shift')->get();
        $child->load(['squads']);
        return view('admin.children.edit', compact('child', 'squads'));
    }

    /**
     * Обновить данные ребенка
     */
    public function childUpdate(Request $request, Child $child)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'squads' => 'nullable|array',
            'squads.*' => 'exists:squads,id',
            'notes' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ], [
            'full_name.required' => 'ФИО ребенка обязательно для заполнения',
            'birth_date.required' => 'Дата рождения обязательна для заполнения',
            'birth_date.before' => 'Дата рождения должна быть в прошлом',
            'squads.*.exists' => 'Один из выбранных отрядов не существует',
            'avatar.image' => 'Файл должен быть изображением',
            'avatar.mimes' => 'Поддерживаемые форматы: JPEG, PNG, JPG, GIF',
            'avatar.max' => 'Размер изображения не должен превышать 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $currentSquadIds = $child->squads()->pluck('squad_id')->toArray();
        $newSquadIds = $request->squads ?? [];

        // Обработка аватара
        $avatarPath = $child->avatar;
        
        // Если нужно удалить аватар
        if ($request->remove_avatar) {
            if ($child->avatar && \Storage::disk('public')->exists($child->avatar)) {
                \Storage::disk('public')->delete($child->avatar);
            }
            $avatarPath = null;
        }
        
        // Если загружен новый аватар
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар
            if ($child->avatar && \Storage::disk('public')->exists($child->avatar)) {
                \Storage::disk('public')->delete($child->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars/children', 'public');
        }

        $child->update([
            'full_name' => $request->full_name,
            'birth_date' => $request->birth_date,
            'notes' => $request->notes,
            'avatar' => $avatarPath,
        ]);

        // Обновляем связи с отрядами
        $child->squads()->sync($request->squads ?? []);

        return redirect()->route('admin.children.show', $child)
                        ->with('success', 'Данные ребенка обновлены');
    }

    /**
     * Удалить ребенка
     */
    public function childDestroy(Child $child)
    {
        // Проверяем, состоит ли ребенок в каких-либо отрядах
        if ($child->squads()->exists()) {
            $squadNames = $child->squads()->pluck('name')->toArray();
            $squadsList = implode(', ', $squadNames);
            
            return redirect()->back()
                           ->with('error', 'Нельзя удалить ребенка "' . $child->full_name . '", так как он состоит в отрядах: ' . $squadsList . '. Сначала исключите ребенка из всех отрядов.');
        }

        $childName = $child->full_name;
        
        // Удаляем аватар если есть
        if ($child->avatar && \Storage::disk('public')->exists($child->avatar)) {
            \Storage::disk('public')->delete($child->avatar);
        }
        
        $child->delete();
        
        return redirect()->route('admin.children.index')
                        ->with('success', 'Ребенок "' . $childName . '" удален');
    }

    /**
     * Импорт детей из CSV файла
     */
    public function childrenImportCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv_file.required' => 'Выберите CSV файл для загрузки',
            'csv_file.mimes' => 'Файл должен быть в формате CSV',
            'csv_file.max' => 'Размер файла не должен превышать 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $file = $request->file('csv_file');
            $csvData = file_get_contents($file->getRealPath());
            
            // Определяем кодировку и конвертируем в UTF-8 если нужно
            $encoding = mb_detect_encoding($csvData, ['UTF-8', 'Windows-1251', 'CP1251'], true);
            if ($encoding !== 'UTF-8') {
                $csvData = mb_convert_encoding($csvData, 'UTF-8', $encoding);
            }
            
            $lines = explode("\n", $csvData);
            $imported = 0;
            $errors = [];
            $duplicates = 0;

            foreach ($lines as $lineNumber => $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                $data = str_getcsv($line, ';');
                
                if (count($data) < 2) {
                    $errors[] = "Строка " . ($lineNumber + 1) . ": недостаточно данных (нужно ФИО и дата рождения)";
                    continue;
                }

                $fullName = trim($data[0]);
                $birthDateStr = trim($data[1]);

                if (empty($fullName) || empty($birthDateStr)) {
                    $errors[] = "Строка " . ($lineNumber + 1) . ": пустые данные";
                    continue;
                }

                // Парсим дату рождения в формате DD.MM.YYYY
                $birthDateParts = explode('.', $birthDateStr);
                if (count($birthDateParts) !== 3) {
                    $errors[] = "Строка " . ($lineNumber + 1) . ": неверный формат даты '$birthDateStr' (ожидается ДД.ММ.ГГГГ)";
                    continue;
                }

                $day = (int) $birthDateParts[0];
                $month = (int) $birthDateParts[1];
                $year = (int) $birthDateParts[2];

                if (!checkdate($month, $day, $year)) {
                    $errors[] = "Строка " . ($lineNumber + 1) . ": некорректная дата '$birthDateStr'";
                    continue;
                }

                $birthDate = sprintf('%04d-%02d-%02d', $year, $month, $day);

                // Проверяем, не существует ли уже ребенок с таким именем и датой рождения
                $existingChild = Child::where('full_name', $fullName)
                                    ->where('birth_date', $birthDate)
                                    ->first();

                if ($existingChild) {
                    $duplicates++;
                    continue;
                }

                // Создаем ребенка
                Child::create([
                    'full_name' => $fullName,
                    'birth_date' => $birthDate,
                ]);

                $imported++;
            }

            $message = "Импорт завершен. Добавлено детей: $imported";
            if ($duplicates > 0) {
                $message .= ", пропущено дубликатов: $duplicates";
            }
            if (!empty($errors)) {
                $message .= ", ошибок: " . count($errors);
            }

            if (!empty($errors)) {
                return redirect()->route('admin.children.index')
                               ->with('warning', $message)
                               ->with('import_errors', $errors);
            }

            return redirect()->route('admin.children.index')
                           ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Ошибка при обработке файла: ' . $e->getMessage());
        }
    }

    /**
     * Показать форму отметки досрочного выезда
     */
    public function childEarlyDepartureForm($shiftId, $squadId, $childId)
    {
        $shift = Shift::findOrFail($shiftId);
        $squad = Squad::findOrFail($squadId);
        $child = Child::findOrFail($childId);

        // Проверяем, что отряд принадлежит смене
        if ($squad->shift_id != $shift->id) {
            return redirect()->back()
                           ->with('error', 'Отряд не принадлежит указанной смене');
        }

        // Проверяем, что ребенок состоит в этом отряде
        if (!$child->isInSquad($squad->id)) {
            return redirect()->back()
                           ->with('error', 'Ребенок не состоит в указанном отряде');
        }

        return view('admin.children.early-departure', compact('child', 'squad'));
    }

    /**
     * Отметить досрочный выезд ребенка из отряда
     */
    public function childMarkEarlyDeparture(Request $request, $shiftId, $squadId, $childId)
    {
        $shift = Shift::findOrFail($shiftId);
        $squad = Squad::findOrFail($squadId);
        $child = Child::findOrFail($childId);

        // Проверяем, что отряд принадлежит смене
        if ($squad->shift_id != $shift->id) {
            return redirect()->back()
                           ->with('error', 'Отряд не принадлежит указанной смене');
        }

        // Проверяем, что ребенок состоит в этом отряде
        if (!$child->isInSquad($squad->id)) {
            return redirect()->back()
                           ->with('error', 'Ребенок не состоит в указанном отряде');
        }

        $validator = Validator::make($request->all(), [
            'early_departure_date' => 'required|date|after_or_equal:' . now()->subDays(30)->format('Y-m-d') . '|before_or_equal:today',
            'early_departure_reason' => 'required|string|max:1000',
        ], [
            'early_departure_date.required' => 'Дата выезда обязательна для заполнения',
            'early_departure_date.after_or_equal' => 'Дата выезда не может быть более 30 дней назад',
            'early_departure_date.before_or_equal' => 'Дата выезда не может быть в будущем',
            'early_departure_reason.required' => 'Причина выезда обязательна для заполнения',
            'early_departure_reason.max' => 'Причина выезда не должна превышать 1000 символов',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Обновляем pivot-запись
        $child->squads()->updateExistingPivot($squad->id, [
            'early_departure_date' => $request->early_departure_date,
            'early_departure_reason' => $request->early_departure_reason,
        ]);

        return redirect()->route('admin.shifts.squads.show', [$squad->shift, $squad])
                        ->with('success', 'Досрочный выезд ребенка "' . $child->full_name . '" из отряда "' . $squad->name . '" отмечен');
    }

    /**
     * Отменить отметку досрочного выезда из отряда
     */
    public function childCancelEarlyDeparture($shiftId, $squadId, $childId)
    {
        $shift = Shift::findOrFail($shiftId);
        $squad = Squad::findOrFail($squadId);
        $child = Child::findOrFail($childId);

        // Проверяем, что отряд принадлежит смене
        if ($squad->shift_id != $shift->id) {
            return redirect()->back()
                           ->with('error', 'Отряд не принадлежит указанной смене');
        }

        // Проверяем, что ребенок состоит в этом отряде
        if (!$child->isInSquad($squad->id)) {
            return redirect()->back()
                           ->with('error', 'Ребенок не состоит в указанном отряде');
        }

        // Обновляем pivot-запись
        $child->squads()->updateExistingPivot($squad->id, [
            'early_departure_date' => null,
            'early_departure_reason' => null,
        ]);

        return redirect()->route('admin.shifts.squads.show', [$squad->shift, $squad])
                        ->with('success', 'Отметка досрочного выезда для ребенка "' . $child->full_name . '" из отряда "' . $squad->name . '" отменена');
    }

    // ========== VOUCHERS (ПУТЕВКИ) ==========

    /**
     * Показать список путевок (детей на смене)
     */
    public function vouchersIndex(Shift $shift)
    {
        $children = $shift->children()
                          ->with(['squads' => function($query) use ($shift) {
                              $query->where('shift_id', $shift->id);
                          }])
                          ->orderBy('full_name')
                          ->paginate(20);

        $totalChildren = $shift->children()->count();
        $childrenInSquads = $shift->children()
                                  ->whereHas('squads', function($query) use ($shift) {
                                      $query->where('shift_id', $shift->id);
                                  })
                                  ->count();
        $childrenWithoutSquads = $totalChildren - $childrenInSquads;

        return view('admin.shifts.vouchers.index', compact(
            'shift', 
            'children', 
            'totalChildren', 
            'childrenInSquads', 
            'childrenWithoutSquads'
        ));
    }

    /**
     * Показать форму создания путевки
     */
    public function voucherCreate(Shift $shift)
    {
        // Получаем детей, которые еще не на этой смене
        $availableChildren = Child::whereDoesntHave('shifts', function($query) use ($shift) {
            $query->where('shift_id', $shift->id);
        })->orderBy('full_name')->get();

        return view('admin.shifts.vouchers.create', compact('shift', 'availableChildren'));
    }

    /**
     * Создать путевку
     */
    public function voucherStore(Request $request, Shift $shift)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|exists:children,id',
            'room_number' => 'nullable|string|max:50',
            'questionnaire' => 'nullable|string',
            'medical_info' => 'nullable|string',
            'dietary_requirements' => 'nullable|string',
            'roommate_preferences' => 'nullable|string',
        ], [
            'child_id.required' => 'Выберите ребенка',
            'child_id.exists' => 'Выбранный ребенок не найден',
            'room_number.max' => 'Номер комнаты не должен превышать 50 символов',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $child = Child::find($request->child_id);

        // Проверяем, нет ли уже путевки для этого ребенка на эту смену
        if ($child->hasVoucherForShift($shift->id)) {
            return redirect()->back()
                           ->withErrors(['child_id' => 'Ребенок уже имеет путевку на эту смену'])
                           ->withInput();
        }

        // Создаем путевку
        $shift->children()->attach($child->id, [
            'room_number' => $request->room_number,
            'questionnaire' => $request->questionnaire,
            'medical_info' => $request->medical_info,
            'dietary_requirements' => $request->dietary_requirements,
            'roommate_preferences' => $request->roommate_preferences,
        ]);

        return redirect()->route('admin.shifts.vouchers.index', $shift)
                        ->with('success', 'Путевка для ребенка "' . $child->full_name . '" создана');
    }

    /**
     * Показать детали путевки
     */
    public function voucherShow(Shift $shift, Child $child)
    {
        // Проверяем, что ребенок на этой смене
        if (!$child->hasVoucherForShift($shift->id)) {
            return redirect()->route('admin.shifts.vouchers.index', $shift)
                           ->with('error', 'Ребенок не найден на этой смене');
        }

        $voucher = $child->getVoucherForShift($shift->id);
        $child->load(['squads' => function($query) use ($shift) {
            $query->where('shift_id', $shift->id);
        }]);

        return view('admin.shifts.vouchers.show', compact('shift', 'child', 'voucher'));
    }

    /**
     * Показать форму редактирования путевки
     */
    public function voucherEdit(Shift $shift, Child $child)
    {
        // Проверяем, что ребенок на этой смене
        if (!$child->hasVoucherForShift($shift->id)) {
            return redirect()->route('admin.shifts.vouchers.index', $shift)
                           ->with('error', 'Ребенок не найден на этой смене');
        }

        $voucher = $child->getVoucherForShift($shift->id);

        return view('admin.shifts.vouchers.edit', compact('shift', 'child', 'voucher'));
    }

    /**
     * Обновить путевку
     */
    public function voucherUpdate(Request $request, Shift $shift, Child $child)
    {
        // Проверяем, что ребенок на этой смене
        if (!$child->hasVoucherForShift($shift->id)) {
            return redirect()->route('admin.shifts.vouchers.index', $shift)
                           ->with('error', 'Ребенок не найден на этой смене');
        }

        $validator = Validator::make($request->all(), [
            'room_number' => 'nullable|string|max:50',
            'questionnaire' => 'nullable|string',
            'medical_info' => 'nullable|string',
            'dietary_requirements' => 'nullable|string',
            'roommate_preferences' => 'nullable|string',
        ], [
            'room_number.max' => 'Номер комнаты не должен превышать 50 символов',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Обновляем данные путевки
        $shift->children()->updateExistingPivot($child->id, [
            'room_number' => $request->room_number,
            'questionnaire' => $request->questionnaire,
            'medical_info' => $request->medical_info,
            'dietary_requirements' => $request->dietary_requirements,
            'roommate_preferences' => $request->roommate_preferences,
        ]);

        return redirect()->route('admin.shifts.vouchers.show', [$shift, $child])
                        ->with('success', 'Данные путевки обновлены');
    }

    /**
     * Удалить путевку
     */
    public function voucherDestroy(Shift $shift, Child $child)
    {
        // Проверяем, что ребенок на этой смене
        if (!$child->hasVoucherForShift($shift->id)) {
            return redirect()->route('admin.shifts.vouchers.index', $shift)
                           ->with('error', 'Ребенок не найден на этой смене');
        }

        // Проверяем, не состоит ли ребенок в отрядах на этой смене
        $squadsOnShift = $child->squads()->where('shift_id', $shift->id)->count();
        if ($squadsOnShift > 0) {
            return redirect()->back()
                           ->with('error', 'Нельзя удалить путевку ребенка "' . $child->full_name . '", так как он состоит в отрядах на этой смене. Сначала исключите ребенка из всех отрядов.');
        }

        $childName = $child->full_name;
        
        // Удаляем путевку
        $shift->children()->detach($child->id);
        
        return redirect()->route('admin.shifts.vouchers.index', $shift)
                        ->with('success', 'Путевка ребенка "' . $childName . '" удалена');
    }
}
