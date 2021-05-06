<?php

namespace edgewizz\mcqt\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Edgewizz\Edgecontent\Models\ProblemSetQues;
use Edgewizz\Mcqt\Models\McqtAns;
use Edgewizz\Mcqt\Models\McqtQues;
use Illuminate\Http\Request;

class McqtController extends Controller
{
    public function store(Request $request){
        $pmQ = new McqtQues();
        $pmQ->question              = $request->question;
        $pmQ->format_title          = $request->format_title;
        $pmQ->difficulty_level_id   = $request->difficulty_level_id;
        $pmQ->hint                  = $request->hint;
        $pmQ->translation           = $request->translation;
        $pmQ->save();
        /* answer1 */
        if($request->answer_1){
            $answer_1                   = new McqtAns();
            $answer_1->question_id      = $pmQ->id;
            $answer_1->answer           = $request->answer_1;
            if ($request->ans_correct_1) {
                $answer_1->arrange      = 1;
            }
            $answer_1->eng_word         = $request->eng_word1;
            $answer_1->save();
        }
        /* //answer1 */
        /* answer2 */
        if($request->answer_2){
            $answer_2                   = new McqtAns();
            $answer_2->question_id      = $pmQ->id;
            $answer_2->answer           = $request->answer_2;
            if ($request->ans_correct_2) {
                $answer_2->arrange      = 1;
            }
            $answer_2->eng_word         = $request->eng_word2;
            $answer_2->save();
        }
        /* //answer2 */
        /* answer3 */
        if($request->answer_3){
            $answer_3                   = new McqtAns();
            $answer_3->question_id      = $pmQ->id;
            $answer_3->answer           = $request->answer_3;
            if ($request->ans_correct_3) {
                $answer_3->arrange      = 1;
            }
            $answer_3->eng_word         = $request->eng_word3;
            $answer_3->save();
        }
        /* //answer3 */
        /* answer4 */
        if($request->answer_4){
            $answer_4                   = new McqtAns();
            $answer_4->question_id      = $pmQ->id;
            $answer_4->answer           = $request->answer_4;
            if ($request->ans_correct_4) {
                $answer_4->arrange      = 1;
            }
            $answer_4->eng_word         = $request->eng_word4;
            $answer_4->save();
        }
        /* //answer4 */
        /* answer5 */
        if($request->answer_5){
            $answer_5                   = new McqtAns();
            $answer_5->question_id      = $pmQ->id;
            $answer_5->answer           = $request->answer_5;
            if ($request->ans_correct_5) {
                $answer_5->arrange      = 1;
            }
            $answer_5->eng_word         = $request->eng_word5;
            $answer_5->save();
        }
        /* //answer5 */
        /* answer6 */
        if($request->answer_6){
            $answer_6                   = new McqtAns();
            $answer_6->question_id      = $pmQ->id;
            $answer_6->answer           = $request->answer_6;
            if ($request->ans_correct_6) {
                $answer_6->arrange      = 1;
            }
            $answer_6->eng_word         = $request->eng_word6;
            $answer_6->save();
        }
        /* //answer6 */
        
        if($request->problem_set_id && $request->format_type_id){
            $pbq = new ProblemSetQues();
            $pbq->problem_set_id        = $request->problem_set_id;
            $pbq->question_id           = $pmQ->id;
            $pbq->format_type_id        = $request->format_type_id;
            $pbq->save();
        }
        return back();
    }
    public function edit($id, Request $request){
        $q = McqtQues::where('id', $id)->first();
        if($request->format_title){
            $q->format_title        = $request->format_title;
        }
        $q->question                = $request->question;
        $q->difficulty_level_id     = $request->difficulty_level_id;
        $q->hint                    = $request->hint;
        $q->translation             = $request->translation;
        $q->save();
        $answers = McqtAns::where('question_id', $q->id)->get();
        foreach($answers as $ans){
            $inputAnswer = 'answer'.$ans->id;
            if($request->$inputAnswer){
                $inputArrange = 'ans_correct'.$ans->id;
                $ans->answer = $request->$inputAnswer;
                $inputEngWord = 'eng_word'.$ans->id;
                $ans->eng_word = $request->$inputEngWord;
                if($request->$inputArrange){
                    $ans->arrange = 1;
                }else{
                    $ans->arrange = 0;
                }
                $ans->save();
            }
        }
        
        return back();
    }

    /* public function inactive($id){
        $f = McqaAns::where('id', $id)->first();
        $f->active = '0';
        $f->save();
        return back();
    }
    public function active($id){
        $f = McqaAns::where('id', $id)->first();
        $f->active = '1';
        $f->save();
        return back();
    } */

    public function imagecsv($question_image, $images){
        foreach($images as $valueImage){
            $uploadImage = explode(".", $valueImage->getClientOriginalName());
            if($uploadImage[0] == $question_image){
                // dd($valueImage);
                $media = new Media();
                $valueImage->storeAs('public/question_images', time() . $valueImage->getClientOriginalName());
                $media->url = 'question_images/' . time() . $valueImage->getClientOriginalName();
                $media->save();
                return $media->id;
            }
        }
    }
    public function csv_upload(Request $request){
        $file = $request->file('file');
        // $images = $request->file('images');
        $audio = $request->file('audio');

        // dd($file);
        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        // Valid File Extensions
        $valid_extension = array("csv");
        // 2MB in Bytes
        $maxFileSize = 2097152;
        // Check file extension
        if (in_array(strtolower($extension), $valid_extension)) {
            // Check file size
            if ($fileSize <= $maxFileSize) {
                // File upload location
                $location = 'uploads';
                // Upload file
                $file->move($location, $filename);
                // Import CSV to Database
                $filepath = public_path($location . "/" . $filename);
                // Reading file
                $file = fopen($filepath, "r");
                $importData_arr = array();
                $i = 0;
                while (($filedata = fgetcsv($file, 1000, ",")) !== false) {
                    $num = count($filedata);
                    // Skip first row (Remove below comment if you want to skip the first row)
                    if ($i == 0) {
                        $i++;
                        continue;
                    }
                    for ($c = 0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata[$c];
                    }
                    $i++;
                }
                fclose($file);
                // Insert to MySQL database
                foreach ($importData_arr as $importData) {
                    $insertData = array(
                        "question"      => $importData[1],
                        "translation"   => $importData[2],

                        "answer1"       => $importData[3],
                        "arrange1"      => $importData[4],
                        "eng_word1"     => $importData[5],

                        "answer2"       => $importData[6],
                        "arrange2"      => $importData[7],
                        "eng_word2"     => $importData[8],

                        "answer3"       => $importData[9],
                        "arrange3"      => $importData[10],
                        "eng_word3"     => $importData[11],

                        "answer4"       => $importData[12],
                        "arrange4"      => $importData[13],
                        "eng_word4"     => $importData[14],
                        
                        "answer5"       => $importData[15],
                        "arrange5"      => $importData[16],
                        "eng_word5"     => $importData[17],

                        "answer6"       => $importData[18],
                        "arrange6"      => $importData[19],
                        "eng_word6"     => $importData[20],

                        "level"         => $importData[21],
                        "comment"       => $importData[22],

                    );
                    // var_dump($insertData['answer1']);
                    /*  */
                    if ($insertData['question']) {
                        $fill_Q = new McqtQues();
                        $fill_Q->question       = $insertData['question'];
                        if ($insertData['translation'] == '-') {} else {
                            $fill_Q->translation    = $insertData['translation'];
                        }
                        if ($insertData['comment'] == '-') {} else {
                            $fill_Q->hint           = $insertData['comment'];
                        }
                        if($request->format_title){
                            $fill_Q->format_title = $request->format_title;
                        }
                        if(!empty($insertData['level'])){
                            if($insertData['level'] == 'easy'){
                                $fill_Q->difficulty_level_id = 1;
                            }else if($insertData['level'] == 'medium'){
                                $fill_Q->difficulty_level_id = 2;
                            }else if($insertData['level'] == 'hard'){
                                $fill_Q->difficulty_level_id = 3;
                            }
                        }
                        $fill_Q->save();
                        if($request->problem_set_id && $request->format_type_id){
                            $pbq = new ProblemSetQues();
                            $pbq->problem_set_id    = $request->problem_set_id;
                            $pbq->question_id       = $fill_Q->id;
                            $pbq->format_type_id    = $request->format_type_id;
                            $pbq->save();
                        }

                        if ($insertData['answer1'] == '-') {
                        } else {
                            $f_Ans1 = new McqtAns();
                            $f_Ans1->question_id    = $fill_Q->id;
                            $f_Ans1->answer         = $insertData['answer1'];
                            $f_Ans1->arrange        = $insertData['arrange1'];
                            if ($insertData['eng_word1'] == '-') {} else {
                                $f_Ans1->eng_word       = $insertData['eng_word1'];
                            }
                            $f_Ans1->save();
                        }
                        if ($insertData['answer2'] == '-') {
                        } else {
                            $f_Ans2 = new McqtAns();
                            $f_Ans2->question_id    = $fill_Q->id;
                            $f_Ans2->answer         = $insertData['answer2'];
                            $f_Ans2->arrange        = $insertData['arrange2'];
                            if ($insertData['eng_word2'] == '-') {} else {
                                $f_Ans2->eng_word       = $insertData['eng_word2'];
                            }
                            $f_Ans2->save();
                        }
                        if ($insertData['answer3'] == '-') {
                        } else {
                            $f_Ans3 = new McqtAns();
                            $f_Ans3->question_id    = $fill_Q->id;
                            $f_Ans3->answer         = $insertData['answer3'];
                            $f_Ans3->arrange        = $insertData['arrange3'];
                            if ($insertData['eng_word3'] == '-') {} else {
                                $f_Ans3->eng_word       = $insertData['eng_word3'];
                            }
                            $f_Ans3->save();
                        }
                        if ($insertData['answer4'] == '-') {
                        } else {
                            $f_Ans4 = new McqtAns();
                            $f_Ans4->question_id    = $fill_Q->id;
                            $f_Ans4->answer         = $insertData['answer4'];
                            $f_Ans4->arrange        = $insertData['arrange4'];
                            if ($insertData['eng_word4'] == '-') {} else {
                                $f_Ans4->eng_word       = $insertData['eng_word4'];
                            }
                            $f_Ans4->save();
                        }
                        if ($insertData['answer5'] == '-') {
                        } else {
                            $f_Ans5 = new McqtAns();
                            $f_Ans5->question_id    = $fill_Q->id;
                            $f_Ans5->answer         = $insertData['answer5'];
                            $f_Ans5->arrange        = $insertData['arrange5'];
                            if ($insertData['eng_word5'] == '-') {} else {
                                $f_Ans5->eng_word       = $insertData['eng_word5'];
                            }
                            $f_Ans5->save();
                        }
                        if ($insertData['answer6'] == '-') {
                        } else {
                            $f_Ans6 = new McqtAns();
                            $f_Ans6->question_id    = $fill_Q->id;
                            $f_Ans6->answer         = $insertData['answer6'];
                            $f_Ans6->arrange        = $insertData['arrange6'];
                            if ($insertData['eng_word6'] == '-') {} else {
                                $f_Ans6->eng_word       = $insertData['eng_word6'];
                            }
                            $f_Ans6->save();
                        }

                    }
                    /*  */
                }
                // Session::flash('message', 'Import Successful.');
            } else {
                // Session::flash('message', 'File too large. File must be less than 2MB.');
            }
        } else {
            // Session::flash('message', 'Invalid File Extension.');
        }
        return back();
    }

}
