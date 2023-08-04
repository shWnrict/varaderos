<?php require_once('config.php'); 

// Check if search parameter is set in the URL
if (isset($_GET['search'])) {
    $title = "Search Result for '" . $_GET['search'] . "'";
}

$whereData = "";

// Check if search parameter is set in the URL
if (isset($_GET['search'])) {
    $whereData = " and (product_name LIKE '%" . $_GET['search'] . "%' or description LIKE '%" . $_GET['search'] . "%')";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Varaderos | Confirmation</title>
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Small Navbar (Top Navbar) -->
    <nav class="navbar navbar-expand-lg smallNav  justify-content-center align-items-center">
        <!-- Add your small navbar content here -->
        <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item">
                <!-- Logo on the left -->
                <a class="navbar-brand" href="#">
                    <img data-aos="slide-up" src="./assets/img/Modal-logo.png" alt="Logo" height="100">
                </a>
            </li>
            <li class=" nav-item ml-3 ">
                <form class="form-inline">
                    <input id="searchInput" class="form-control mr-sm-2" type="search" placeholder="Search"
                        aria-label="Search">
                    <button class="btn btn-outline-light btn-search btn-md my-2 my-sm-0 " type="button"
                        onclick="performSearch()">Search</button>
                </form>
            </li>
        </ul>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-light bg-light  shadow">




        <!-- Responsive Toggle Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation links on the right -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="HomePage.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#contactModal">Contact Us</a>
                </li>

            </ul>
        </div>
    </nav>

    <section class="landing-page shadow ">

        <button class=" btn btn-outline-light btn-md shop-button">Shop Now</button>
        </div>

    </section>

    <!-- Section-->
<section class="py-5">
<div class="col-12">
                <h2 class="text-center mt-4 titles">BESTSELLERS</h2>
            </div>
    <div class="container-fluid px-4 px-lg-5 mt-5">

        <!-- Search result title -->
        <?php
        if (isset($_GET['search'])) {
            echo "<h4 class='text-center'><b>Search Result for '" . $_GET['search'] . "'</b></h4>";
        }
        ?>

        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

            <?php
            $products = $conn->query("SELECT * FROM `products` where status = 1 {$whereData} order by rand()  LIMIT 3 ");
            while ($row = $products->fetch_assoc()) :
                $upload_path = base_app . '/uploads/product_' . $row['id'];
                $img = "";
                if (is_dir($upload_path)) {
                    $fileO = scandir($upload_path);
                    if (isset($fileO[2]))
                        $img = "uploads/product_" . $row['id'] . "/" . $fileO[2];
                }
                $inventory = $conn->query("SELECT * FROM inventory where product_id = " . $row['id']);
                $inv = array();
                while ($ir = $inventory->fetch_assoc()) {
                    $inv[$ir['size']] = number_format($ir['price']);
                }
            ?>
                <!-- Product card -->
                <div class="col mb-5" > 
                    <div class="card h-100 product-item" data-aos="fade-up" data-aos-delay="500">
                        <!-- Product image-->
                        <img class="card-img-top w-100" src="<?php echo validate_image($img) ?>" loading="lazy" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo $row['product_name'] ?></h5>
                                <!-- Product price-->
                                <?php foreach ($inv as $k => $v) : ?>
                                    <span><b><?php echo $k ?>: </b><?php echo $v ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="text-center">
                            <!-- You can use any icon library for stars, like Font Awesome -->
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-flat btn-primary" href="view_product.php?id=<?php echo md5($row['id']) ?>">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- No product listed message -->
            <?php
            if ($products->num_rows <= 0) {
                echo "<h4 class='text-center'><b>No Product Listed.</b></h4>";
            }
            ?>
        </div>
    </div>
</section>
  

    <section class="py-5 Varaderos_abstract">
        <div class="container">
            <div class="row">
                <!-- First Container -->
                <div class="col-md-4 cont1">
                    <h1 class="text-right">Vara</h1>
                    <p class="text-right">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sed
                        volutpat ex. Nunc vitae tristique urna. Ut mi justo, fringilla nec cursus id, pellentesque
                        efficitur dui. Vestibulum velit lectus, pellentesque eget justo vel, ultricies elementum erat.
                    </p>
                </div>

                <!-- Second Container -->
                <div class="col-md-4 cont2">
                    <img src="./assets/img/BLOODY MARY TRANS.png" class="img-fluid floating-element" alt="Image 2">
                </div>

                <!-- Third Container -->
                <div class="col-md-4 cont3">
                    <h1 class="text-left">dero</h1>
                    <p class="text-left">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sed
                        volutpat ex. Nunc vitae tristique urna. Ut mi justo, fringilla nec cursus id, pellentesque
                        efficitur dui. Vestibulum velit lectus, pellentesque eget justo vel, ultricies elementum erat.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 ">
        <div class="container">
            <div class="row">
                <!-- First Container -->
                <div class="col-lg-4 founderCont" >
                    <h1 class="text-right">The Founder</h1>
                    <p class="text-right">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sed
                        volutpat ex. Nunc vitae tristique urna. Ut mi justo, fringilla nec cursus id, pellentesque
                        efficitur dui. Vestibulum velit lectus, pellentesque eget justo vel, ultricies elementum erat.
                    </p>
                </div>

                <!-- Second Container -->
                <div class="col-lg-8 col-sm-12 otherCont" >

                    <div class="col-lg-12 founderCont1" data-aos="slide-up" data-aos-delay="600">
                        <h1 class="text-left">The Founder</h1>
                        <p class="text-left">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sed
                            volutpat ex. Nunc vitae tristique urna. Ut mi justo, fringilla nec cursus id, pellentesque
                            efficitur dui. Vestibulum velit lectus, pellentesque eget justo vel, ultricies elementum
                            erat.</p>

                    </div>

                    <div class="col-lg-12 NegroniCont" data-aos="slide-up" data-aos-delay="700">
                        <h1 class="text-right">The Founder</h1>
                        <p class="text-right">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sed
                            volutpat ex. Nunc vitae tristique urna. Ut mi justo, fringilla nec cursus id, pellentesque
                            efficitur dui. Vestibulum velit lectus, pellentesque eget justo vel, ultricies elementum
                            erat.</p>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="subscribeSec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 excludeCont">
                    <h1 class="text-center " data-aos="fade-up" data-aos-delay="700">Get exclusive offers & rewards</h1>
                    <p class="text-center">Sign up for our members portal to receive some exclusive <br> offers &
                        rewards. it's easy and free!</p>
                    <div class="container mt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-sm-8">
                                <div class="input-group">
                                    <input type="email" class="form-control email-form" placeholder="Your Email Address"
                                        aria-label="Your Email Address" aria-describedby="subscribe-btn">
                                    <div class="input-group-append">
                                        <button class="btn btn-sub" type="button" id="subscribe-btn">Subscribe</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- First Column - Logo -->
                <div class="col-md-4">
                    <div class="logo" data-aos="fade-up" data-aos-delay="800"><img src="./assets/img/Modal-logo.png"
                            alt="" class="img-fluid"></div>

                </div>

                <!-- Second Column - Contact Us -->
                <div class="col-md-4">
                    <h5>Connect with us</h5>
                    <div class="social-icons">
                        <i class="fab fa-facebook"></i>
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i> <span>+639 112 231</span>
                    </div>
                </div>

                <!-- Third Column - Information -->
                <div class="col-md-4 infoCont">
                    <h5>Information</h5>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Exchange Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>




    <!-- Modal -->
    <div class="modal fade" id="ageModal" tabindex="-1" role="dialog" aria-labelledby="ageModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="./assets/img/Modal-logo.png" alt="" class="modal_logo">
                    <h3>Are you over 18 years old?</h3>
                    <button type="button" class="btn btn-primary mr-3" id="over18Btn">Yes, I'm over 18</button>
                    <button type="button" class="btn btn-primary" id="notOver18Btn">No, I'm not</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Contact Us</h5>
                    <!-- Add your logo image here -->
                    <img src="./assets/img/Modal-logo.png" alt="Logo" class="modal-logo">
                    <br>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
                <!-- Modal Footer (You can add a footer if needed) -->
                <!-- <div class="modal-footer">
                Footer content here...
            </div> -->
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        $(document).ready(function () {
            // Show the age verification modal on page load
            $('#ageModal').modal('show');

            // Handle the "No, I'm not" button click
            $('#notOver18Btn').on('click', function () {
                alert("You must be 18 years or older to enter this site.");
                window.location.href = "index.php"; // Redirect to a different page for underage users
            });

            // Handle the "Yes, I'm over 18" button click
            $('#over18Btn').on('click', function () {
                $('#ageModal').modal('hide');
                // You can perform any other actions here for users over 18
            });
        });


        function performSearch() {
            const searchTerm = $("#searchInput").val().trim();
            if (searchTerm === "") {
                // Clear previous search highlights if any
                $(".highlight").removeClass("highlight");
                return;
            }

            // Regular expression to match the search term, ignore case
            const regex = new RegExp(`\\b${searchTerm}\\b`, "gi");

            // Iterate through all elements on the page
            $("*").each(function () {
                // Check if the element contains text
                if ($(this).children().length === 0 && $(this).text().trim() !== "") {
                    // Replace matched words with a span containing the highlight class
                    $(this).html($(this).text().replace(regex, "<span class='highlight'>$&</span>"));
                }
            });
        }
    </script>

</body>

</html>