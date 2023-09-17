@component('components.header', ['gtitle' => ''])
@endcomponent

    <div id="blog" class="blog-main pad-top-100 pad-bottom-100 parallax">
        <div class="container">
            <div class="row blog-detail">
                <div class="col-lg-2 col-md-1 col-sm-1"></div>
                <div class="blog-content col-lg-5 col-md-8 col-sm-9 col-xs-11">
                	<br /><br /><br />
                    <h4 class="direct-txt">/ Bài viết</h4>
                    <br />
                    <h3 class="detail-title">
					@php echo $blog->blog_title @endphp
				    </h3>
                    <br />
                    <div class="blog-box clearfix">
                        <div class="detail-content">

                        @php echo $blog->blog_content @endphp

                        </div>
                        <!-- end detail-content -->
                        <div class="detail-bottom">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                    <div class="fb-like" data-href="https://www.facebook.com/batstyle.aothun.aophongcach" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4"></div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6"></div>
                            </div>
                        </div>
                        <!-- end detail-bottom -->

                    </div>
                    <!-- end blog-box -->

                </div>
                <!-- end col -->
                <div class="col-lg-2 col-md-1 col-sm-1"></div>
            </div>
            <!-- end row -->
            <section id="s-blogs" class="wrapper nonbg">
				<div class="inner">

					<header class="align-center">
						<h2>Bài viết mới nhất</h2>
					</header>

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

							</div>
						@endforeach
						</div>

						<a href="{{ route('blogs.list') }}" class="link-more">See more</a>
				</div>
				<div class="clear"></div>
            </section>
            
        </div>
        <!-- end container -->
    </div>
    <!-- end blog-main -->

    @component('components.footer', ['gc_by_id' => $gc_by_id, 'arr_tags' => $arr_tags])
@endcomponent