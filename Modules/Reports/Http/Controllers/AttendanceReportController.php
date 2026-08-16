<?php

namespace Modules\Reports\Http\Controllers;

use App\Repositories\User\UserInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Reports\Http\Requests\AttendanceReportRequest;
use Modules\Reports\Repositories\AttendanceReport\AttendanceReportInterface;
// use Barryvdh\DomPDF\Facade\Pdf;
class AttendanceReportController extends Controller
{
    protected $repo,$userRepo;
     public function __construct(
            AttendanceReportInterface $repo,
            UserInterface $userRepo
        )
     {
        $this->repo   = $repo;
        $this->userRepo = $userRepo;
     }
    public function index(Request $request)
    {
        $employees = $this->userRepo->getReportsUsers();
        return view('reports::attendance_reports.index',compact('employees','request'));
    }

    public function report(AttendanceReportRequest $request)
    {
        if(isUser()):
            $request['employee_id'] = Auth::user()->id;
        endif;
        $employees   = $this->userRepo->getReportsUsers();
        $reportTotal = $this->repo->getReport($request);
        $request['getHolidays'] = $this->repo->getHolidays($request);
        $employee    = $this->userRepo->edit($request->employee_id);
        return view('reports::attendance_reports.index',compact('employees','reportTotal','request','employee'));
    }
    public function reportPrint(Request $request)
    {
        if(isUser()):
            $request['employee_id'] = Auth::user()->id;
        endif;
        $reportTotal = $this->repo->getReport($request);
        $employee    = $this->userRepo->edit($request->employee_id);
        return view('reports::attendance_reports.attendance_reports_print',compact('reportTotal','request','employee'));

    }

    public function reportPdfDownload(Request $request){
        if(isUser()):
            $request['employee_id'] = Auth::user()->id;
        endif;
        $reportTotal = $this->repo->getReport($request);
        $employee    = $this->userRepo->edit($request->employee_id);
        $pdf = Pdf::loadView('reports::attendance_reports.attendance_report_pdf', compact('reportTotal','request','employee'));
        return $pdf->download('Attendance-report-'.date('Y-m-d-His').'.pdf');
    }

}
