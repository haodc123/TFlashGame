@component('components.header', ['gtitle' => ''])
@endcomponent

    <div id="blog" class="blog-main pad-top-100 pad-bottom-100 parallax">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                	<br /><br /><br />
                    <h2 class="block-title text-center">
					Danh sách bài viết
					
				</h2>                
                
                <section id="s-blogs" class="wrapper nonbg">
				<div class="inner">

					<!-- 4 Column Video Section -->
						<div class="flex flex-3">
						@foreach ($bloglist as $blog)
							<div class="video col">

								<div class="image fit">
									<img src="../images/blogs/{{ $blog->blog_thumb }}" alt="Dịch vụ Media, Ảnh - {{ $blog->blog_title }}" />
								</div>

								<p class="caption">
									{{ $blog->blog_title }}
								</p>

								<a href="{{ route('blogs.show', ['title'=>$blog->blog_title_slug]) }}" class="link"><span>Click Me</span></a>
                                
                                <div class="blog-dit">
                                    <p class="caption">@php echo $blog->updated_at @endphp</p>
                                </div>
							</div>
						@endforeach
						</div>

				</div>
				<div class="clear"></div>
				</section>

                    <!-- end blog-box -->

                    <div class="blog-btn-v">
                        
                    </div>

                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end blog-main -->

    @component('components.footer', ['gc_by_id' => $gc_by_id, 'arr_tags' => $arr_tags])
@endcomponent