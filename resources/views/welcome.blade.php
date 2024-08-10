<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Tracker</title>
    <link rel="stylesheet" href="{{ mix('css/custom.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.css">
</head>
<body>
    <div id="app">
    </div>

    <script src="{{ mix('js/app.js') }}" defer></script>
    {{-- <script src="https://kit.fontawesome.com/097b5d635a.js" crossorigin="anonymous"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var sidebarCollapse = document.getElementById('sidebarCollapse');
            var sidebar = document.getElementById('sidebar');

            if(sidebarCollapse ){
                sidebarCollapse.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
