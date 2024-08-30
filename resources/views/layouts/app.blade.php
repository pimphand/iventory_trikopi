<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Starter Page | Konrix - Responsive Tailwind Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="coderthemes" name="author">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset('assets') }}/css/app.min.css" rel="stylesheet" type="text/css">

    <!-- Icons css -->
    <link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets') }}/js/config.js"></script>
</head>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <!-- Sidenav Menu -->
        <div class="app-menu">

            <!-- Sidenav Brand Logo -->
            <a href="index.html" class="logo-box">
                <!-- Light Brand Logo -->
                <div class="logo-light">
                    <img src="{{ asset('assets') }}/images/logo-light.png" class="logo-lg h-6" alt="Light logo">
                    <img src="{{ asset('assets') }}/images/logo-sm.png" class="logo-sm" alt="Small logo">
                </div>

                <!-- Dark Brand Logo -->
                <div class="logo-dark">
                    <img src="{{ asset('assets') }}/images/logo-dark.png" class="logo-lg h-6" alt="Dark logo">
                    <img src="{{ asset('assets') }}/images/logo-sm.png" class="logo-sm" alt="Small logo">
                </div>
            </a>

            <!-- Sidenav Menu Toggle Button -->
            <button id="button-hover-toggle" class="absolute top-5 end-2 rounded-full p-1.5">
                <span class="sr-only">Menu Toggle Button</span>
                <i class="mgc_round_line text-xl"></i>
            </button>

            <!--- Menu -->
            <x-sidebar></x-sidebar>
        </div>
        <!-- Sidenav Menu End  -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <x-header></x-header>

            <main class="flex-grow p-6">

                @yield('content')


            </main>

            <!-- Footer Start -->
            <footer class="footer h-16 flex items-center px-6 bg-white shadow dark:bg-gray-800">
                <div class="flex justify-center w-full gap-4">
                    <div>
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Konrix - <a href="https://coderthemes.com/" target="_blank">Coderthemes</a>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <!-- Back to Top Button -->
    <button data-toggle="back-to-top"
        class="fixed hidden h-10 w-10 items-center justify-center rounded-full z-10 bottom-20 end-14 p-2.5 bg-primary cursor-pointer shadow-lg text-white">
        <i class="mgc_arrow_up_line text-lg"></i>
    </button>

    <x-theme></x-theme>

    <!-- Plugin Js -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets') }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets') }}/libs/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets') }}/libs/@frostui/tailwindcss/frostui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        //ajax request header token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function requestData(method, url, data, callback) {
            $.ajax({
                type: method,
                url: url,
                data: data,
                //format of data you are sending send file
                contentType: false,
                processData: false,
                success: function (response) {
                    callback(null,response);
                },
                error: function (error) {
                    callback(error,null);
                }
            });
        }

        function renderPagination(data) {
            const paginationNav = $('#pagination-nav');
            paginationNav.empty();  // Clear existing pagination

            // Previous button
            let prevLink = data.links.prev ? data.links.prev : '#';
            paginationNav.append(`
                <a class="text-gray-400 hover:text-primary p-4 inline-flex items-center gap-2 font-medium rounded-md"
                href="${prevLink}" ${!data.links.prev ? 'aria-disabled="true"' : ''}>
                    <span aria-hidden="true">«</span>
                    <span class="sr-only">Previous</span>
                </a>
            `);

            // Page number buttons
            data.meta.links.forEach(link => {
                // Check if the link is a number (not "Previous" or "Next")
                if (isNaN(link.label)) return;

                paginationNav.append(`
                    <a class="w-10 h-10 ${link.active ? 'bg-primary text-white' : 'text-gray-400 hover:text-primary'}
                            p-4 inline-flex items-center text-sm font-medium rounded-full"
                    href="${link.url ? link.url : '#'}" ${link.active ? 'aria-current="page"' : ''} ${!link.url ? 'aria-disabled="true"' : ''}>
                        ${link.label}
                    </a>
                `);
            });

            // Next button
            let nextLink = data.links.next ? data.links.next : '#';
            paginationNav.append(`
                <a class="text-gray-400 hover:text-primary p-4 inline-flex items-center gap-2 font-medium rounded-md"
                href="${nextLink}" ${!data.links.next ? 'aria-disabled="true"' : ''}>
                    <span class="sr-only">Next</span>
                    <span aria-hidden="true">»</span>
                </a>
            `);
        }
    </script>
    <!-- App Js -->
    <script src="{{ asset('assets') }}/js/app.js"></script>

    @stack('js')
</body>

</html>
