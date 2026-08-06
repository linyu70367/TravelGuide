<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield("title")</title>
  <!-- <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" /> -->
  <link rel="stylesheet" href="/css/googleFont.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/fontAwesome.css">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/my.css') }}">
  <link rel="stylesheet" href="/css/front/news.css">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/front/header.css">
  <link rel="stylesheet" href="/css/lightbox.min.css">
  <!-- <link rel="stylesheet" href="/css/sweetAert2.css"> -->
  <script src="https://code.jquery.com/jquery-4.0.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios@1.13.2/dist/axios.min.js"></script>
  <script src="/js/lightbox.min.js"></script>
  @stack("style")
  @stack('script')
</head>

<body>
  @if (Session::has("message"))
  <script>
    Swal.fire({
      title: "{{ Session::get('message') }}",
      text: "",
      icon: "success"
    });
  </script>
  @endif


  @include("front.header")


  @yield("content")
  <footer>&copy; 2026 AI輔助全端程式與專案設計班. All rights reserved.</footer>

</body>
<script>
  axios.defaults.withCredentials = true;
  axios.defaults.withXSRFToken = true;
  $(function() {
    $("#logout_btn").on("click", async function(e) {
      e.preventDefault();

      try {
        let res = await axios.post(
          "/member/logout"
        );
        console.log(res.data);
        location.href = "/views";
      } catch (error) {
        console.log(error.response);
      }
    });
  });
</script>

</html>