<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Void_;

class Loan extends Model
{
    use HasFactory;
    
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function isPaid(){
        $category_translation = $this->payments->where('pay_status', 'PENDING')->count();
        return $category_translation ==0;
    }

    
}
