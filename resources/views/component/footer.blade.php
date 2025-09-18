<!-- Footer Start -->
<div class="container-fluid custom-color text-light wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5 px-lg-5">
        <div class="row gy-5 gx-4">

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white mb-4">Address</h5>
                <p><i class="fa fa-map-marker-alt me-3"></i>73 Basement, Ekta Enclave Society, Lakhanpur, Khyora, Kanpur,
                    UP 208024</p>
                <p><a href="tel:+918299012292"><i class="fa fa-phone-alt me-3"></i>+918299012292</p></a>
                <p><a href="mailto:realvictorygroups@gmail.com"><i
                            class="fa fa-envelope me-3"></i>realvictorygroups@gmail.com</p></a>
                <div class="d-flex pt-3">
                    <a class="btn btn-outline-light btn-social me-2" href="https://wa.me/+918299012292"><i
                            class="fab fa-whatsapp"></i></a>
                    <a class="btn btn-outline-light btn-social me-2"
                        href="https://www.facebook.com/realvictorygroups/"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social me-2"
                        href="https://www.instagram.com/realvictorygroups/"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-outline-light btn-social"
                        href="http://linkedin.com/company/realvictorygroups/?originalSubdomain=in"><i
                            class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-4">Quick Links</h5>
                <div class="d-flex flex-column">
                    <a class="btn btn-link text-light text-start" href="{{ route('about') }}">About Us</a>
                    <a class="btn btn-link text-light text-start" href="{{ route('contact') }}">Contact Us</a>
                    <a class="btn btn-link text-light text-start" href="{{ route('privacy-policy') }}">Privacy Policy</a>
                    <a class="btn btn-link text-light text-start" href="{{ route('term') }}">Terms & Conditions</a>
                    <a class="btn btn-link text-light text-start" href="{{ route('refund_policy') }}">Return Policy</a>
                    <a class="btn btn-link text-light text-start" href="{{ route('career') }}">Career</a>
                </div>
            </div>

            <!-- Our Services -->
            @php

                $serviceCategories = App\Models\ServiceCategory::with('serviceDetails')
                    ->where('status', 'active')
                    ->get();
            @endphp
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-4">Our Services</h5>
                <div class="d-flex flex-column">
                    @foreach ($serviceCategories as $serviceCategory)
                        <a class="btn btn-link text-light text-start"
                            href="{{ route('servicedetail', $serviceCategory->slug) }}">{{ $serviceCategory->name }}</a>
                    @endforeach


                </div>
            </div>

            <!-- Download Application -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-white mb-3">Download Our App</h5>

                <!-- App Store Buttons -->
                <div class="d-flex flex-column gap-3">

                    <p class="text-white mb-4" style="font-size: 0.95rem;">
                        Get instant access to our services, updates, and offers anytime, anywhere. Download our app
                        today and stay connected on the go!
                    </p>
                    <a href="https://apps.apple.com/in/app/real-victory-groups-daily-post/id6746252505 "
                        class="d-flex align-items-center bg-dark text-white rounded px-3 py-2 shadow-sm text-decoration-none hover-opacity">
                        <i class="fab fa-apple fa-2x me-3"></i>
                        <div class="text-start">
                            <small class="d-block">Download on the</small>
                            <strong class="fs-6">App Store</strong>
                        </div>
                    </a>

                    <a href="https://play.google.com/store/apps/details?id=com.rvg.chat_real_victory&pcampaignid=web_share"
                        class="d-flex align-items-center bg-dark text-white rounded px-3 py-2 shadow-sm text-decoration-none hover-opacity">
                        <i class="fab fa-google-play fa-2x me-3"></i>
                        <div class="text-start">
                            <small class="d-block">Get it on</small>
                            <strong class="fs-6">Google Play</strong>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Copyright -->
    <div class="container px-lg-5 border-top border-light pt-4 mt-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; <a class="text-white fw-bold" href="https://realvictorygroups.com/">2025 Real Victory Groups</a>. All
                Rights Reserved.
            </div>

        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<a href="#" class="btn btn-dark btn-lg-square back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>
