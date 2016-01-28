<!DOCTYPE html>
<html lang="en">
<head>
    <title>BMGI | Dashboard</title>
    @include('partials.head')
</head>
<body class="page-body">
    <div class="page-container">
    <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    @include('partials.sidebar')
    <div class="main-content">
        @include('partials.top_section')
        <hr/>

        @yield('content')

        <!-- Footer -->
        @include("partials.footer.php")
    </div>
</div>
@include("partials.jlinks")
</body>
</html>