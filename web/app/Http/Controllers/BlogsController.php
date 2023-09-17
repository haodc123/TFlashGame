<?php

namespace App\Http\Controllers;

use App\Blogs;
use App\Game;
use App\GameCats;
use App\User;
use App\Enums\ResStatus;
use App\Http\Requests\PreBlogReq;
use Session;
use Illuminate\Http\Request;

class BlogsController extends Controller
{

    private $blog;
    private $customer;

    public function save_blog(PreBlogReq $request) {
        $validated = $request->validated();
        
        $request->merge(['f_status' => 0]);
        $this->do_save_blog($request);
        
        // Prepare success info to view
        $type = 'blog';
        return redirect()->route('next', ['type' => $type]);
        
    }

    public function update_blog(PreBlogReq $request) {
        if ($request->blog_action == "delete") {
        
            $this->do_delete_blog($request);
            
            // Prepare success info to view
            $type = 'deleteblog';
        } else {
            $validated = $request->validated();
            
            $this->do_update_blog($request);
            
            // Prepare success info to view
            $type = 'updateblog';
        }

        return redirect()->route('next', ['type' => $type]);
        
    }

    public function do_save_blog($request) {
        $input = $request->all();

        $blog = new Blogs();
        $blog->blog_title = $input['f_title'];
        $blog->blog_content = $input['f_content'];
        $blog->blog_cat = $input['f_cat'];
        $blog->blog_thumb = $input['f_thumb'];
        $blog->blog_status = $input['f_status'];
        $blog->id = $input['f_id'];
        $blog->save_blog();
    }

    public function do_update_blog($request) {
        $input = $request->all();
        
        $blog = new Blogs();
        $blog->blog_title = $input['f_title'];
        $blog->blog_content = $input['f_content'];
        $blog->blog_cat = $input['f_cat'];
        $blog->blog_thumb = $input['f_thumb'];
        $blog->blog_status = $input['f_status'];
        $blog->id = $input['f_id'];
        $blog->update_blog();
    }

    public function do_delete_blog($request) {
        $input = $request->all();
        $blog = new Blogs();
        $blog->id = $input['f_id'];
        $blog->delete_blog();
    }

    public function ctr_list_blogs() {
        $blog = new Blogs();
        $list_blogs = $blog->getAllBlogsPagination();

        return view('control.blogs', [
            'blogs' => $list_blogs
        ]);
    }

    public function ctr_filter_blogs(Request $request) {
        $name = $request->name;
        $tel = $request->tel;
        $addr = $request->addr;
        $food = $request->food;
        $status = $request->status;

        $blog = new Blogs();
        $list_blogs = $blog->getBlogsFilter($name, $tel, $addr, $food, $status);

        return response()->json([
            "data" => $list_blogs,
            "status" => ResStatus::Success
        ]);
    }


    private $blogs;

    public function index() {

        $this->blogs = new Blogs();
        $bloglist = $this->blogs->getAllBlogs();

        $gc = new GameCats();
        $gc_all = $gc->getArrayCatRichInfo();
        $gc_by_id = $gc_all[0];
        $arr_tags = $gc_all[1];

        return view('blogs.blogs', [
            'bloglist' => $bloglist,
            'gc_by_id' => $gc_by_id,
            'arr_tags' => array_unique($arr_tags)
        ]);
    }

    public function show($title) {

        $this->blogs = new Blogs();
        $blog = $this->blogs->getBlogWithTitle($title);
        $bloglist = $this->blogs->getAllBlogs(6);

        $gc = new GameCats();
        $gc_all = $gc->getArrayCatRichInfo();
        $gc_by_id = $gc_all[0];
        $arr_tags = $gc_all[1];

        return view('blogs.detail', [
            'blog' => $blog,
            'bloglist' => $bloglist,
            'gc_by_id' => $gc_by_id,
            'arr_tags' => array_unique($arr_tags)
        ]);
    }

    public function edt_get_blogcats() {
        $blogcats = new BlogCats();
        return response()->json([
            'data' => $blogcats->getAllBlogCats(), 
            'status' => ResStatus::Success
        ]);
    }

    public function edt_del_blogcat(Request $request) {
        $blogcats = new BlogCats();
        $status = $blogcats->del($request->id);

        return response()->json([
            'status' => $status
        ]);
    }

    public function edt_update_blogcat(Request $request) {
        $blogcats = new BlogCats();
        $status = $blogcats->update_by_id(
            $request->id,
            $request->name,
            $request->order,
            $request->status
        );
        return response()->json([
            'status' => $status
        ]);
    }
    
    public function edt_get_blogs() {
        $blogs = new Blogs();
        $blogcats = new BlogCats();
        $res = [
            'blogs' => $blogs->getAllBlogsPagination(), 
            'blogcats' => $blogcats->getAllBlogCats()
        ];
        return response()->json([
            'data' => $res, 
            'status' => ResStatus::Success
        ]);
    }

    public function edt_del_blog(Request $request) {
        $blogs = new Blogs();
        $status = $blogs->del($request->id);

        return response()->json([
            'status' => $status
        ]);
    }
}
