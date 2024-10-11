<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Comments;

class CommentsController extends Controller
{
    
        public function new($id=null){
                
            $page = array(
                'menu'=>'comments',
                'subtitle'=>'Comment Entry',
            );
            if($id){
                $comment = Comments::find( $id );
                $page['comment'] = $comment;
                $page['subtitle'] = 'Edit Comment Entry';
            
            }
            
            return view('admin.comments-new')->with('page',$page);

    }
    

    public function get(){

        $search_this = $_GET['search']['value'];
        $start = $_GET['start'];
        $length = $_GET['length'];
        $orderby = $_GET['order'][0]['column'];
        $orderdir = $_GET['order'][0]['dir'];
        
        $comments = Comments::select('*');
        $comments->where('status','!=','deleted');
        //$users->where('branch_id','=',$branch_id);
        $comments->orderBy('created_at', 'desc');
        
        $comments2 = Comments::select(array('id'));
        //$users2->where('branch_id','=',$branch_id);
        $comments2->where('status','!=','deleted');
    
        if ($search_this) {
                    
                    $comments->where('comment','like', '%'.strtolower($search_this).'%');
                    $comments->where('lot_id','=',$search_this);

        }

        $comments_count = $records_filtered = $comments->get()->count();
        $records_filtered = $comments->count();
        if ($search_this) {
            $records_filtered = $comments->count();
        }

        if($orderby == 1)
            $orderby = 'name';
        else
            $orderby = 'id';
        
        $comments = $comments
            ->orderBy($orderby, $orderdir)
            ->skip( $start )->take($length)
            ->get();
        
        $data = array();

        if ($comments)
        {
            
            foreach($comments as $k=>$comment) {
            
                $btn = '<!--<a class="btn  btn-primary btn-md btn-edit"  href="'.url('comments/view/'.$comment->id).'"><i class="fa fa-pencil" aria-hidden="true"></i>View</a>&nbsp;--><a class="btn  btn-success btn-md btn-edit"  href="'.url('comments/edit/'.$comment->id).'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;<a class="btn  btn-danger btn-md btn-delete"  data-id="'.$comment->id.'" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                
                
                array_push($data,array(
                        $comment->id,
                        $comment->acct_id,
                        $comment->lot_id,
                        $comment->comment,
                        date('m/d/Y', strtotime($comment->created_at)),
                        $btn,
                        
                ));
            }
            
            
        }
        echo json_encode(array('draw'=>$_GET['draw'],'recordsTotal'=>$comments_count,'recordsFiltered'=>$records_filtered,'data'=>$data));
        
        
    }

    public function delete($id) {
        
        $comment =  Comments::find($id);
        $comment->status = 'deleted';
        if($comment->save()) {
            echo json_encode(["msg"=>'success']);
        } else {
                echo json_encode(["msg"=>'failed']);
        }
            
    }

    


    public function store(Request $request) {
            
            
            $validate = Validator::make($request->all(), [
            
                'acct_id' => 'required',
                'lot_id' => 'required',
                'comment' => 'required',

            ],[
                'acct_id.required' => 'Shopify User ID is required',
                'lot_id.required' => 'Lot number ID is required',
                'comment.required' => 'Comment is required',
                
                
            ]);
            if($validate->fails()){
                return back()->withErrors($validate->errors())->withInput();
            }



            if(request()->id){
            //update
                $item = Comments::find(request()->id);
                $message = 'Bidding  edited successfully!';
            } else {
            //create
                $item = new Comments();
                $message = 'Bidding  added successfully!';
            
            }


            
            $item->acct_id = request()->acct_id;
            $item->lot_id = request()->lot_id;
            $item->comment = request()->comment;
            $item->submitedfrom = isset(request()->submitedfrom)?request()->submitedfrom:'';
           
        
            if( $item->save() ){ // success

                if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                    return redirect('/comments')->with('added', $message);
                } else {
                    //submitted from shopify
                    echo json_encode(["status"=>'success','msg'=>$message]);
                }

            } else { // failed
            
                
                if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                    return redirect('/comments')->with('failed', 'New item failed to add!');
                } else {
                    //submitted from shopify
                    echo json_encode(["status"=>'failed','msg'=>'Failed to list',"lotId"=>'']);
                }


            }
            

    }
    



    public function index(){
    
        

        $page = array(
            'menu'=>'comments',
        );
        //var_dump($products);
        return view('admin.comments')->with('page',$page);

    }


}
