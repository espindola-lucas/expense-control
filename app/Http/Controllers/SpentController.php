<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Spent\DeleteSpentAction;
use App\Actions\Spent\GetDashboardDataAction;
use App\Actions\Spent\StoreSpentAction;
use App\Actions\Spent\UpdateSpentAction;
use App\Helpers\Helps;
use App\Http\Requests\Spent\StoreSpentRequest;
use App\Http\Requests\Spent\UpdateSpentRequest;
use App\Models\Spent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpentController extends Controller
{
    public function index(Request $request, GetDashboardDataAction $action)
    {
        $user = Auth::user();

        $data = $action->execute(
            userId:         $user->id,
            type:           $request->query('type', 'personal'),
            selectedMonth:  $request->input('period'),
            filterText:     $request->input('search'),
            startDateParam: $request->input('start_date'),
            endDateParam:   $request->input('end_date'),
        );

        if ($data['isFilter']) {
            return view('dashboard', [
                'spents'            => $data['spents'],
                'user'              => $user,
                'allPeriods'        => $data['allPeriods'],
                'message'           => false,
                'currentDate'       => Helps::getDate(),
                'lastConfiguration' => $data['lastConfiguration'],
                'hasConfiguration'  => $data['hasConfiguration'],
                'type'              => $data['type'],
                'hasBothConfig'     => false,
                'onlyFilter'        => true,
                'branchName'        => Helps::getGitBranchName(),
            ]);
        }

        return view('dashboard', [
            'spents'            => $data['spents'],
            'user'              => $user,
            'allPeriods'        => $data['allPeriods'],
            'monthly_balance'   => $data['monthly_balance'],
            'lastConfiguration' => $data['lastConfiguration'],
            'currentDate'       => Helps::getDate(),
            'percentageUsed'    => $data['percentageUsed'],
            'message'           => false,
            'branchName'        => Helps::getGitBranchName(),
            'hasConfiguration'  => $data['hasConfiguration'],
            'type'              => $data['type'],
            'hasBothConfig'     => false,
            'onlyFilter'        => false,
        ]);
    }

    public function create()
    {
        return view('abm.create', [
            'user'       => Auth::user(),
            'type'       => 'personal',
            'isSell'     => false,
            'storeRoute' => 'spents.store',
            'dateField'  => 'expense_date',
            'nameField'  => 'spentName',
            'labelDate'  => 'Dia de la compra',
            'labelName'  => 'Nombre del gasto',
            'today'      => now()->format('Y-m-d'),
        ]);
    }

    public function store(StoreSpentRequest $request, StoreSpentAction $action)
    {
        $action->execute($request->validated(), Auth::id());

        return redirect()->route('dashboard')->with('success', 'Gasto agregado exitosamente.');
    }

    public function edit(Spent $spent)
    {
        $spent->name = trim($spent->name);

        return view('abm.edit', [
            'spent' => $spent,
            'user'  => Auth::user(),
        ]);
    }

    public function update(UpdateSpentRequest $request, Spent $spent, UpdateSpentAction $action)
    {
        $action->execute($spent, $request->validated());

        return redirect('dashboard');
    }

    public function destroy(Request $request, Spent $spent, DeleteSpentAction $action)
    {
        $action->execute($spent);

        return redirect()->route('dashboard', [
            'year'  => $request->input('year'),
            'month' => $request->input('month'),
        ]);
    }
}
