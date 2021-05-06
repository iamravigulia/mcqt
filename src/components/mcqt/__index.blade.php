<style>
    table {
        background: #fff;
        width: 100%;
        border: 0;
    }

    th {}

    td {
        border-top: 1px solid #999;
        padding: 5px;
    }

    tr:nth-child(odd) {
        background: #ddd;
    }

</style>
<table>
    <thead>
        <th>#</th>
        <th>Question</th>
        <th>Answers</th>
        <th>Created at</th>
        <th>Updated at</th>
    </thead>
    <tbody>
        @php
        $fmt_mcqa_ques = DB::table('fmt_mcqa_ques')->get();
        @endphp
        @foreach ($fmt_mcqa_ques as $que)
        <tr>
            <td>{{$que->id}}</td>
            <td>{{$que->question}}</td>
            @php $fmt_mcqa_ans = DB::table('fmt_mcqa_ans')->where('question_id', $que->id)->get() @endphp
            <td>
                <ul>
                    @foreach ($fmt_mcqa_ans as $ans)
                    @php $ans_media = DB::table('media')->where('id', $ans->media_id)->first() @endphp
                    <div  @if($ans->arrange == 1) style="color:blue;" @endif>
                        <span>{{$ans->answer}}</span>
                        <audio controls="controls" src="{{url('/')}}/storage/{{$ans_media->url}}"></audio>
                    </div>
                    @endforeach
                </ul>
            </td>
            <td>{{date('F d, Y',strtotime($que->created_at))}}</td>
            <td>{{date('F d, Y',strtotime($que->updated_at))}}</td>
            <td>
                <a style="font-size: 12px; background:#4450f3; color:#fff; border-radius:4px; padding:2px 4px;" href="javascript:void(0);"  onclick="modalMCQA({{$que->id}})">Edit</a>
                <a style="font-size: 12px; background:#f23939; color:#fff; border-radius:4px; padding:2px 4px;" href="{{route('fmt.mcqa.inactive', $que->id)}}">Delete</a>
            </td>
        </tr>
        <x-mcqa.edit :message="$que->id"/>
        @endforeach
    </tbody>
</table>
<script>
    function modalMCQA($id){
        var modal = document.getElementById('modalMCQA'+$id);
        modal.classList.remove("hidden");
    }
    function closemodalMCQA($id){
        var modal = document.getElementById('modalMCQA'+$id);
        modal.classList.add("hidden");
    }
</script>