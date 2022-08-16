<?php

namespace App\Http\Controllers;

use App\Models\ApiBaseMethod;
use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /***
     * method to Create the Loan Request by the User
     */
    public function createRequest(Request $request)
    {

        try {
            //code...
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $validator = Validator::make($request->all(), [
                    'amount' => "required|numeric|gt:0",
                    'term' => "required|numeric|gt:0",
                ]);

                if ($validator->fails()) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                    }
                }

                $loan = new Loan();
                $loan->amount = $request->amount;
                $loan->term  = $request->term;
                $loan->user_id = $request->user()->id;
                $loan->save();

                $payment = $loan->amount / $loan->term;
                $date = time();
                for ($i = 0; $i < $loan->term; $i++) {
                    $repay = new LoanPayment();
                    $repay->due_amount = $payment;
                    $repay->due_date = date('Y-m-d', ($date + 86400 * 7 * $i));
                    $repay->loan_id = $loan->id;
                    $repay->save();
                }

                return ApiBaseMethod::sendResponse(['request_id'=>time() . $loan->id], 'Loan Request Processed Successfully');
            } else {
                return ApiBaseMethod::sendError('Please check your url');
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Internal Server Error with.', $th->getMessage());
            //throw $th;
        }
    }

    /***
     * method to Approve the Loan Request by the Admin
     */
    public function approveRequest(Request $request)
    {
        try {
            //code...
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $validator = Validator::make($request->all(), [
                    'loan_id' => "required|numeric|gt:0",
                    'status' => "required|numeric"

                ]);

                if ($validator->fails()) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                    }
                }

                $loan = Loan::find($request->loan_id);

                if (isset($loan)) {
                    $loan->status = $request->status == 0 ? 'REJECTED' : 'APPROVED';
                    $loan->approved_by = $request->user()->id;
                    $loan->approved_at = date('Y-m-d');
                    $loan->save();
                }

                return ApiBaseMethod::sendResponse(array(), 'Loan Request ' . ($request->status == 0 ? 'REJECTED' : 'APPROVED') . ' Successfully');
            } else {
                return ApiBaseMethod::sendError('Please check your url');
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Internal Server Error with.', $th->getMessage());
            //throw $th;
        }
    }

    /***
     * method to get the list of Loan Request by the User
     */
    public function getLoans(Request $request)
    {
        try {
            //code...
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $loans = Loan::where('user_id', $request->user()->id)->get();

                $loan_res = [];
                if (isset($loans)) {
                    foreach ($loans as $loan) {
                        $loan_temp = new \stdClass();
                        $loan_temp->id = $loan->id;
                        $loan_temp->amount = $loan->amount;
                        $loan_temp->term = $loan->term;
                        $loan_temp->status = $loan->status;
                        $loan_temp->created_at = date('Y-m-d', strtotime($loan->created_at));
                        $loan_temp->approved_at = $loan->approved_at ? date('Y-m-d', strtotime($loan->created_at)) : '';
                        $loan_temp->approved_by = $loan->approved_by;
                        $pay_arr = [];
                        foreach ($loan->payments as $pay) {
                            $loan_temp_pay = new \stdClass();
                            $loan_temp_pay->id = $pay->id;
                            $loan_temp_pay->due_amount = $pay->due_amount;
                            $loan_temp_pay->due_date = date('Y-m-d', strtotime($pay->due_date));
                            $loan_temp_pay->pay_status = $pay->pay_status;
                            $pay_arr[] = $loan_temp_pay;
                        }
                        $loan_temp->scheuled_payments = $pay_arr;
                        $loan_res[] = $loan_temp;
                    }
                }

                return ApiBaseMethod::sendResponse($loan_res, 'Loan Requests List Successful');
            } else {
                return ApiBaseMethod::sendError('Please check your url');
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Internal Server Error with.', $th->getMessage());
            //throw $th;
        }
    }

    /***
     * method to Pay the Loan scheduled payments by the Admin
     */
    public function payLoan(Request $request)
    {

        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $validator = Validator::make($request->all(), [
                    'amount' => "required|gt:0",
                    'pay_id' => "required",
                ]);

                if ($validator->fails()) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                    }
                }

                $loan_pay = LoanPayment::find($request->pay_id);
                if (isset($loan_pay)) {
                    $loan = $loan_pay->loan;

                    if($loan->status !='APPROVED')
                    return ApiBaseMethod::sendError('Invalid Request to pay the loan');

                    if ($request->amount >= $loan_pay->due_amount) {
                        $loan_pay->pay_status = 'PAID';
                        $loan_pay->pay_date = date('Y-m-d');
                        $loan_pay->save();
                    } else {
                        return ApiBaseMethod::sendError("Please add amount greater or equal to {$loan_pay->due_amount}");
                    }

                    if ($loan->isPaid()) {
                        $loan->status = 'PAID';
                        $loan->save();
                    }
                    return ApiBaseMethod::sendResponse(['payment_id'=> time() . $loan->id], 'Loan Paid Successfully');
                } else
                    return ApiBaseMethod::sendError('Invalid loan payment request id');
            } else {
                return ApiBaseMethod::sendError('Please check your url');
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Internal Server Error with.', $th->getMessage());
            //throw $th;
        }
    }
}
