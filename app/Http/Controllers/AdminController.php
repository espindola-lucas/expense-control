<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

        $users = $this->get_info_users();

        // HOSTNAME, HTTP_USER_AGENT, HTTP_HOST, REMOTE_ADDR
        $server = $_SERVER;
        // dd($server);
        
        $scriptPath = storage_path('app/scripts/user_info.py');
        // dd($scriptPath);
        // exec("python3 $scriptPath");

        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];

        return view('admin.admin', [
            'users' => $users,
            'user' => $user,
            'server' => $server,
            'footerInformation' => $footerInformation
        ]);
    }

    public function destroy(User $user){
        $user->delete();
        return redirect()->route('is_admin.index');
    }

    private function get_info_users(){
        $users = User::select('id', 'name', 'email')
            ->get();

        return $users;
    }
}
