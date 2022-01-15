<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KasbonService;

class KasbonController extends Controller
{
    public function index(Request $request,KasbonService $kasbon){
        $data = $kasbon->getData($request);
        
        return response()->json($data);
    }
    
    public function store(Request $request, KasbonService $kasbon) {

        $store = $kasbon->storeData($request);

        return response()->json($store);
    }
    
    public function setujui($id, KasbonService $kasbon){
        $data = $kasbon->setujui($id);
        
        return response()->json($data);
    }
    
    public function setujuiMasal(Request $request,KasbonService $kasbon){
        $data = $kasbon->setujuiMasal();
        
        return response()->json($data);
    }
}
