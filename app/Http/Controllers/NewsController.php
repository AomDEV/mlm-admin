<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $newsData = News::find(1);
        // $cashWallet = CashWallet::where('user_id', $userId)->first();

        if ($newsData == null) {
            $newsData = new News;
            $newsData->id = 1;
            $newsData->body = "";
            $newsData->save();
        }

        if ($request->isMethod('post')) {

            $form_news = array(
                'body' => $request->get('news_body')
            );
            
            $newsData->update($form_news);

            return view('manage.news', ['newsData' => $newsData]);
        }

        return view('manage.news', ['newsData' => $newsData]);
    }

    public function imageUpload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
        
            $request->file('upload')->move(public_path('images/news'), $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/news/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }

}
