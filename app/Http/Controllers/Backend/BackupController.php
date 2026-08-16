<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Backup\BackupInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    protected $repo;
    public function __construct(BackupInterface $repo){
        $this->repo      = $repo;
    }
    public function index(){
        return view('backend.backup.index');
    }

    public function BackupDownload(){

        if($this->repo->backupDownload()):
            Toastr::success(__('backup_downloaded'),__('success'));
            return redirect()->route('backup.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }
}
