<?php
namespace Edgewizz\Mcqt\Models;

use Illuminate\Database\Eloquent\Model;

class McqtQues extends Model{
    public function answers(){
        return $this->hasMany('Edgewizz\Mcqt\Models\McqtAns', 'question_id');
    }
    protected $table = 'fmt_mcqt_ques';
}