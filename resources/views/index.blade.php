@extends('layouts')

@section('title', '')

@section('metadata')
    <link rel="canonical" href="{{ route('home') }}">

    <meta content="" name="description">
    <meta content="" name="keywords">

    <meta property="og:type" content="">
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:site_name" content="">

    <meta property="og:image" content="{{ asset('assets/img/hero.png') }}">
    <meta property="og:image" content="{{ asset('assets/img/why-us.png') }}">
    <meta property="og:image" content="{{ asset('assets/img/team/biagio.jpg') }}">
    <meta property="og:image" content="{{ asset('assets/img/team/pierluigi.jpg') }}">
    <meta property="og:image" content="{{ asset('assets/img/team/mario.jpg') }}">
    <meta property="og:image" content="{{ asset('assets/img/team/alessandro.jpg') }}">
@endsection

@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
                     data-aos="fade-up" data-aos-delay="200">
                    <h1>LabelSync for Workspace</h1>
                    <h2>Sync your contacts label in Google Workspace</h2>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="{{ route('home') }}#about" class="btn-get-started scrollto">About</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('assets/img/hero.png') }}" alt="Hero Image" class="img-fluid animated">
                </div>
            </div>
        </div>
    </section><!-- End Hero -->

    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>About {{ getSiteName($settings['site_name'] ?? null) }}</h2>
            </div>
            <div class="row content">
                <div class="col-lg-6">
                    <p>Labels in Google Contacts are used to organize your contacts.
                        For example, labels can be used to send emails to an entire Group of people/Department,
                        without having to write dozens of email addresses each time.
                        Often, in companies there is a need to have the contacts/labels synchronized
                        between all employees, and the only way to do this is to export the contacts
                        and re-import them, but the labels cannot be recreated except manually.</p>
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0">
                    <p>Labelsync, our extension for Google Chrome, offers users the solution to synchronize
                        labels and contacts between all users of the same organization,
                        automatically and across all devices used. It will be enough for the Google Workspace admin
                        to install the extension, create the labels, and then have the possibility
                        to choose which labels and with which users to share the information.</p>
                </div>
            </div>
        </div>
    </section><!-- End About Us Section -->

    <!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us section-bg">
        <div class="container-fluid" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-7 d-flex flex-column justify-content-center align-items-stretch order-2 order-lg-1">
                    <div class="content">
                        <h3>
                            Discover our Chrome Extension
                            <strong>a powerful tool designed to enhance your Google Workspace experience</strong>
                        </h3>
                        <p>With an intuitive and simple interface, our extension transforms the way you work,
                            increasing your productivity and convenience.</p>
                    </div>
                    <div class="accordion-list">
                        <ul>
                            <li>
                                <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#why-us-1">
                                    <span>01</span> Real-time Label Synchronization
                                    <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                                </a>
                                <div id="why-us-1" class="collapse show" data-bs-parent=".accordion-list">
                                    <p>Keep contact labels updated across devices in real-time.
                                        Any changes made to labels on one device are automatically reflected
                                        on the others, ensuring consistency and reducing manual effort.</p>
                                </div>
                            </li>
                            <li>
                                <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#why-us-2">
                                    <span>02</span> You choose what to sync
                                    <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                                </a>
                                <div id="why-us-2" class="collapse" data-bs-parent=".accordion-list">
                                    <p>Sync labels based on your rules and preferences.
                                        Define how your labels and contacts sync, making sure the app fits
                                        your specific contact management needs.</p>
                                </div>
                            </li>
                            <li>
                                <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#why-us-3">
                                    <span>03</span> Multi-Platform Support
                                    <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                                </a>
                                <div id="why-us-3" class="collapse" data-bs-parent=".accordion-list">
                                    <p>Seamlessly sync your labels across various platforms and devices,
                                        including web, mobile, and desktop.
                                        This will allow you to access your accurately classified
                                        contacts on any device, improving business productivity.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5 align-items-stretch order-1 order-lg-2 img"
                     style="background-image: url({{ asset('assets/img/why-us.png') }});"
                     data-aos="zoom-in" data-aos-delay="150">&nbsp;</div>
            </div>
        </div>
    </section><!-- End Why Us Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>Our offerings include custom software, web and mobile app development,
                    cloud integration and e-commerce solutions.
                    With a focus on user-friendly interfaces and innovation,
                    we ensure seamless experiences. We also offer ongoing support and strategic
                    advice for sustained growth.</p>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bxl-dribbble"></i></div>
                        <h4><a href="javascript:void(0);">Custom Application Development</a></h4>
                        <p>We create tailor-made software solutions that streamline processes,
                            enhance user experiences and drive efficiency across all business sectors.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bx-file"></i></div>
                        <h4><a href="javascript:void(0);">Google Workspace Integration</a></h4>
                        <p>We seamlessly integrate Google's suite of productivity tools into workflows
                            to improve collaboration and simplify data management.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="300">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bx-tachometer"></i></div>
                        <h4><a href="javascript:void(0);">Mobile App Development</a></h4>
                        <p>We design, build and manage technologies that are versatile,
                            scalable and in line with business needs. Our applications are not only compatible
                            with a wide range of devices and operating systems, but also offer a simple
                            and device-specific user experience.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bx-layer"></i></div>
                        <h4><a href="javascript:void(0);">Google Chrome Extension</a></h4>
                        <p>Extensions are software programs based on web technologies
                            (such as HTML, CSS and JavaScript) that allow users to personalize
                            the Chrome browsing experience and increase productivity.</p>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Services Section -->

    <!-- ======= Team Section ======= -->
    <section id="team" class="team section-bg">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Team</h2>
                <p>Allow us to introduce the talented and passionate people
                    who drive our company's innovation and excellence.
                    With diverse backgrounds and extensive experience,
                    our team is committed to providing exceptional
                    solutions tailored to your needs.</p>
            </div>
            <div class="row">
                <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="member d-flex align-items-start">
                        <div class="pic">
                            <img src="{{ asset('assets/img/team/biagio.jpg') }}" alt="Biagio" class="img-fluid">
                        </div>
                        <div class="member-info">
                            <h4>Biagio Garofalo</h4>
                            <span>Chief Executive Officer</span>
                            <p>With a passion for innovation, he has decades of experience
                                in complex technological environments.</p>
                            <div class="social">
                                <a href="https://twitter.com/biagiogarofalo"><i class="ri-twitter-fill"></i></a>
                                <a href="https://www.facebook.com/biagio.garofalo.96"><i class="ri-facebook-fill"></i></a>
                                <a href="https://www.linkedin.com/in/biagiogarofalo/"><i class="ri-linkedin-box-fill"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="200">
                    <div class="member d-flex align-items-start">
                        <div class="pic">
                            <img src="{{ asset('assets/img/team/pierluigi.jpg') }}" alt="Pierluigi" class="img-fluid">
                        </div>
                        <div class="member-info">
                            <h4>Pierluigi Pisanti</h4>
                            <span>Business Developer Manager</span>
                            <p>A strategic thinker, creates impactful solutions and lasting
                                partnerships that fuel the growth of our company.</p>
                            <div class="social">
                                <a href="https://twitter.com/ppisanti"><i class="ri-twitter-fill"></i></a>
                                <a href="https://www.facebook.com/morch.cuba.7"><i class="ri-facebook-fill"></i></a>
                                <a href="https://www.instagram.com/pierluigi_pisanti/"><i class="ri-instagram-fill"></i></a>
                                <a href="https://www.linkedin.com/in/pierluigipisanti/"><i class="ri-linkedin-box-fill"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="member d-flex align-items-start">
                        <div class="pic">
                            <img src="{{ asset('assets/img/team/mario.jpg') }}" alt="Mario" class="img-fluid">
                        </div>
                        <div class="member-info">
                            <h4>Mario Cortese</h4>
                            <span>CTO</span>
                            <p>I create complex software projects, immersed in the web world since university,
                                I like to accept new challenges every day.</p>
                            <div class="social">
                                <a href="https://www.linkedin.com/in/mario-cortese-a367a796/"><i class="ri-linkedin-box-fill"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member d-flex align-items-start">
                        <div class="pic">
                            <img src="{{ asset('assets/img/team/alessandro.jpg') }}" alt="Alessandro" class="img-fluid">
                        </div>
                        <div class="member-info">
                            <h4>Alessandro Cavaliere</h4>
                            <span>Google Workspace Guru</span>
                            <p>Passionate about my work, and relentlessly determined.
                                Innovation is everything to me, what else is there?</p>
                            <div class="social">
                                <a href="https://www.linkedin.com/in/alessandro-cavaliere-49455915b/"><i class="ri-linkedin-box-fill"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Team Section -->

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Pricing</h2>
                <p>We offer flexible pricing models that consider factors such as the complexity of the app,
                    features required, development time, and ongoing maintenance.
                    This ensures that you receive a competitive and transparent pricing structure
                    that aligns with your budget and goals.Get in touch with us to discuss your project,
                    and we'll provide you with a customized pricing quote based on your unique requirements.</p>
            </div>
            <div class="row">
                @foreach ($plans as $index => $plan)
                    <div class="col-lg-4 @if ($index > 0) mt-4 mt-lg-0 @endif"
                         data-aos="fade-up"
                         data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="box @if ($plan['featured']) featured @endif">
                            <h3>{{ $plan['name'] }}</h3>
                            <h4><sup>{{ $plan['currency'] }}</sup>{{ $plan['price'] }}<span>per {{ $plan['unit'] }}/user</span></h4>
                            <ul>
                                @if ($plan['supported_features'])
                                    @php $supported = explode("\n", $plan['supported_features']); @endphp
                                    @foreach ($supported as $item)
                                        <li><i class="bx bx-check"></i> {{ $item }}</li>
                                    @endforeach
                                @endif
                                @if ($plan['unsupported_features'])
                                    @php $unsupported = explode("\n", $plan['unsupported_features']); @endphp
                                    @foreach ($unsupported as $item)
                                        <li class="na"><i class="bx bx-x"></i> <span>{{ $item }}</span></li>
                                    @endforeach
                                @endif
                            </ul>
                            <a href="" class="buy-btn">Subscribe Now</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section><!-- End Pricing Section -->

    <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="faq" class="faq section-bg">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p>Below are some common but not exhaustive questions.
                    Please feel free to contact us if you have any doubts or inquiries.</p>
            </div>
            <div class="faq-list">
                <ul>
                    <li data-aos="fade-up" data-aos-delay="100">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapse" data-bs-toggle="collapse" data-bs-target="#faq-list-1">
                            What is LabelSync?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
                            <p>Within Google Workspace Contacts, your personal contacts
                                (leads, customers etc) can be grouped via a label.
                                Labelsync allows you to share these contacts
                                with other members of your team.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="200">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-2">
                            How do I sync my labels contact with my Phone or Tablets?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                            <p>Label synchronization ensures that any changes you make
                                to contact labels on one device are automatically
                                updated across all your devices in real time.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="300">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-3">
                            Can I share labels with only some users?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                            <p>Absolutely! Our app allows you to select, only some or all the labels,
                                and the users with whom to synchronize them, leaving full freedom
                                of what and with whom to share the information.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="400">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-4">
                            Is label sharing only performed by Google Administrator?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-4" class="collapse" data-bs-parent=".faq-list">
                            <p>Yes, we think it's best if there is only one source
                                that shares the tags for all users, at least the main ones.
                                Each user can then create their own.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="500">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-5">
                            Is my data secure while using the app?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-5" class="collapse" data-bs-parent=".faq-list">
                            <p>Yes, your data security is a priority.
                                We use advanced encryption and follow industry best practices
                                to safeguard your information.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="500">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-6">
                            Can I try the app before purchasing?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-6" class="collapse" data-bs-parent=".faq-list">
                            <p>Certainly! We offer a free trial period for you to experience
                                the app's features and benefits firsthand before making a decision.</p>
                        </div>
                    </li>

                    <li data-aos="fade-up" data-aos-delay="600">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-7">
                            Why do I need to log is a Google Workspace Administrator to install Labelsync?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-7" class="collapse" data-bs-parent=".faq-list">
                            <p>Our application needs to be installed domain-wide and requires
                                the necessary permissions to access your Google Contacts.
                                Only your Google Workspace administrator has the ability
                                to grant this type of permission.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="700">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-8">
                            What is the difference between Google Shared Contacts and Google Contacts?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-8" class="collapse" data-bs-parent=".faq-list">
                            <p>Google Shared Contacts is also known as the Google Directory.
                                This is a centralized store of contact information.
                                Google Contacts are unique to each Google Workspace user.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="800">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-9">
                            Do normal users also have to install the Labelsync Chrome Extension?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-9" class="collapse" data-bs-parent=".faq-list">
                            <p>Yes, they also have to install it and don't need licenses.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="900">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-list-10">
                            I have hundreds of users, do I have to buy a license for each user?
                            <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="faq-list-10" class="collapse" data-bs-parent=".faq-list">
                            <p>No, you just need to buy licenses only for one or more administrators.
                                It depends on your organization how it is structured.
                                If you have any doubts contact us before purchasing.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section><!-- End Frequently Asked Questions Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p>Reach out to our dedicated team for inquiries, assistance, and partnership opportunities.
                    We're here to provide prompt and helpful responses to your queries.</p>
            </div>
            <div class="row">
                <div class="col-lg-5 d-flex align-items-stretch">
                    <div class="info">
                        <div class="address">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Location:</h4>
                            <p>{{ $settings['contact_address'] ?? '' }}</p>
                        </div>
                        <div class="email">
                            <i class="bi bi-envelope"></i>
                            <h4>Email:</h4>
                            <p>{{ $settings['contact_email'] ?? '' }}</p>
                        </div>
                        <div class="phone">
                            <i class="bi bi-phone"></i>
                            <h4>Call:</h4>
                            <p>{{ $settings['contact_phone'] ?? '' }}</p>
                        </div>
                        <iframe src="{!! $settings['map_link'] ?? '' !!}" frameborder="0"
                                style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
                    <form action="{{ route('contact') }}" method="POST" role="form" class="php-email-form">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Your Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Your Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="10" required></textarea>
                        </div>
                        <div class="my-3">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Your message has been sent. Thank you!</div>
                        </div>
                        <div class="text-center">
                            <button type="submit">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section><!-- End Contact Section -->
@endsection
