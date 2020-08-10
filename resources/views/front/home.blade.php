@extends('layouts.front')
@section('content')
@error('subscription_expired')
<span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
</span>
@enderror

<!-- 10th Home Slider -->
<div class="home10-mainslider">
    <div class="container-fluid p0">
        <div class="main-banner-wrapper home10">
            <div class="banner-style-one">
                <div class="slide slide-one" style="background-image: url({{ asset('frontend_assets') }}/images/banner.jpg);height: 620px;">
                    <div class="container">
                        <div class="row home-content">
                            <div class="col-lg-12 text-center p0">
                                <h2 class="banner_top_title">Welcome</h2>
                                <h3 class="banner-title">Revolutionizing The Real Estate Investing  <br> Industry, One Property At A Time.</h3>
                                <div class="btn"><a href="#" class="banner-btn">Learn More</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.carousel-btn-block banner-carousel-btn -->
        </div><!-- /.main-banner-wrapper -->
    </div>
</div>

<div class="container ovh mt-5 mb-5">
    <div class="row">
        <div class="how-worktop text-center">
            <h3>Cash Investor Network Inc. was created for one purpose, to streamline the way wholesalers and cash buyers do business. Our team of experienced developers, web designers and lead generators have all come together to offer our clients a superior product. Cash Investor Network Inc. is here to help with every aspect of your business.</h3>
        </div>
        <div class="col-lg-6 offset-lg-3">
            <div class="main-title text-center mb40">
                <div class="section-title">
                    <span>Our process is really simple</span>
                        <h2>How Our Service Works</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="single-howit-works">
                    <img src="{{ asset('frontend_assets') }}/images/icon.png" alt="">
                    <h4>Upload Active Contract</h4>
                    <p>Upload your contracts for us to verify. You simply add the property description, price and photos for cash buyers to browse.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="single-howit-works">
                    <img src="{{ asset('frontend_assets') }}/images/2icon.png" alt="">
                    <h4>Browse Properties</h4>
                    <p>Cash Buyers Browse our network looking for a property that meets their buying criteria.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="single-howit-works">
                    <img src="{{ asset('frontend_assets') }}/images/3icon.png" alt="">
                    <h4>Negotiate A Price</h4>
                    <p>Use our anonymous messaging system to negotiate with the seller. Click the Buy Now button when you’re ready to initiate the sale.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Property Cities -->
<section id="property-city" class="property-city pb30 bg-grey">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-4">
                <div class="section-title">
                    <h2>Property Types</h2> 
                    <span>Find the property that <br>
                        fits your buying criteria today</span>
                </div>
            </div>
            <!-- <div class="col-lg-4 col-xl-4">
                <div class="properti_city">
                    <div class="thumb"><img class="img-fluid w100" src="{{ asset('frontend_assets') }}/images/categories/cate_1.jpg" alt="pc1.jpg"></div>
                    <div class="overlay">
                        <div class="details">
                            <h4>Villas</h4>
                            <p>24 Properties</p>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-lg-4 col-xl-4">
                <div class="properti_city">
                    <div class="thumb"><img class="img-fluid w100" src="{{ asset('frontend_assets') }}/images/categories/cate_3.jpg" alt="pc1.jpg"></div>
                    <div class="overlay">
                        <div class="details">
                            <h4>Single Family Residences</h4>
                            <!-- <p>24 Properties</p> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xl-4">
                <div class="properti_city">
                    <div class="thumb"><img class="img-fluid w100" src="{{ asset('frontend_assets') }}/images/categories/cate_4.jpg" alt="pc1.jpg"></div>
                    <div class="overlay">
                        <div class="details">
                            <h4>Multi Unit</h4>
                            <!-- <p>24 Properties</p> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xl-4">
                <div class="properti_city">
                    <div class="thumb"><img class="img-fluid w100" src="{{ asset('frontend_assets') }}/images/categories/cate_2.jpg" alt="pc1.jpg"></div>
                    <div class="overlay">
                        <div class="details">
                            <h4>Commercial <small class="text-white">(Coming Soon)</small></h4>
                            <!-- <p>24 Properties</p> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="col-lg-4 col-xl-4">
                <div class="properti_city">
                    <div class="thumb"><img class="img-fluid w100" src="{{ asset('frontend_assets') }}/images/categories/cate_5.jpg" alt="pc1.jpg"></div>
                    <div class="overlay">
                        <div class="details">
                            <h4>Bunglow</h4>
                            <p>24 Properties</p>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</section>

<!-- Why Chose Us -->
<section id="why-chose" class="whychose_us bgc-f7 pb30 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="main-title text-center">
                    <div class="section-title">
                        <div class="text-center">
                        <h2>Pricing Plans</h2>
                            <p style="line-height: 1.7;">At Cash Investor Network Inc. we don’t believe in long term contracts. Everything we do is on a month to month basis to better serve our clients.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="why_chose_us home10 cash">
                    <div class="icon">
                        <p class="m-0">Absolutely</p>
                        <h1>Free</h1>
                        <h4>Unlimited <br>Access</h4>
                    </div>
                    <div class="details">
                        <h2>Cash Investors</h2>
                        <a href="{{ route('register') }}?type=cash_buyer" class="btn btn-hover">Sign Up</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="why_chose_us home10 wholesale">
                    <div class="icon">
                        <p class="m-0">Price starts at</p>
                        <h1>$0</h1>
                        <h4>Per month</h4>
                    </div>
                    <div class="details">
                        <h2>Wholesalers</h2>
                        <a href="{{ route('register') }}?type=wholesaler" class="btn btn-hover">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection