<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\error_logs;

class ErrorLogController extends Controller
{
     // for error log
     public function ErrorLog(Request $request) {
        $data = $request->all();
        if (!empty($data)) {
            ErrorLog::create([
                'err_text' => $data["err_text"], 
                'file_path'  => $data["file_path"],
                'method'  => $data["method"],
                'parent_method'  => $data["parent_method"], 
                'error_time' => $data["error_time"], 
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s"),
				'type' => $data["type"],
            ]);
            $rt['status'] = 'success';
            return response()->json($rt);
        }
    }    
}
