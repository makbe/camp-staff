<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    /**
     * Показать список всех смен
     */
    public function index(Request $request)
    {
        $query = Shift::withCount(['staff', 'squads'])
            ->withCount(['squads as children_count' => function ($query) {
                $query->join('child_squad', 'squads.id', '=', 'child_squad.squad_id');
            }]);

        // Фильтр по названию
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Фильтр по дате начала
        if ($request->filled('start_date_from')) {
            $query->where('start_date', '>=', $request->start_date_from);
        }

        // Фильтр по дате окончания
        if ($request->filled('start_date_to')) {
            $query->where('start_date', '<=', $request->start_date_to);
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'active':
                    $query->where('start_date', '<=', $now)
                          ->where('end_date', '>=', $now);
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
                case 'completed':
                    $query->where('end_date', '<', $now);
                    break;
            }
        }

        $shifts = $query->orderBy('start_date', 'desc')->paginate(15);
        
        // Сохраняем параметры фильтрации для пагинации
        $shifts->appends($request->query());

        return view('admin.shifts.index', compact('shifts'));
    }

    /**
     * Показать форму создания новой смены
     */
    public function create()
    {
        return view('admin.shifts.create');
    }

    /**
     * Создать новую смену
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:shifts,name',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ], [
            'name.required' => 'Название смены обязательно',
            'name.unique' => 'Смена с таким названием уже существует',
            'start_date.required' => 'Дата начала обязательна',
            'start_date.after_or_equal' => 'Дата начала не может быть в прошлом',
            'end_date.required' => 'Дата окончания обязательна',
            'end_date.after' => 'Дата окончания должна быть позже даты начала',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $shift = Shift::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.shifts.show', $shift)
                        ->with('success', 'Смена успешно создана');
    }

    /**
     * Показать детали смены
     */
    public function show(Shift $shift)
    {
        $shift->load(['staff' => function($query) {
            $query->orderBy('last_name')->orderBy('first_name');
        }]);
        
        return view('admin.shifts.show', compact('shift'));
    }

    /**
     * Показать форму редактирования смены
     */
    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    /**
     * Обновить смену
     */
    public function update(Request $request, Shift $shift)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:shifts,name,' . $shift->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'name.required' => 'Название смены обязательно',
            'name.unique' => 'Смена с таким названием уже существует',
            'start_date.required' => 'Дата начала обязательна',
            'end_date.required' => 'Дата окончания обязательна',
            'end_date.after' => 'Дата окончания должна быть позже даты начала',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $shift->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.shifts.show', $shift)
                        ->with('success', 'Смена успешно обновлена');
    }

    /**
     * Удалить смену
     */
    public function destroy(Shift $shift)
    {
        // Проверяем, есть ли сотрудники в этой смене
        if ($shift->staff()->exists()) {
            return redirect()->back()
                           ->with('error', 'Нельзя удалить смену, в которой есть сотрудники');
        }

        $shift->delete();

        return redirect()->route('admin.shifts.index')
                        ->with('success', 'Смена успешно удалена');
    }
}
