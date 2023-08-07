<?php

function getVideoLink($reel_link)
{
    $reel_id = preg_match('/\/reel\/([\w]+)\//', $reel_link, $matches) ? $matches[1] : null;
    if (!$reel_id) {
        return array("", "");
    }

    $link = "https://www.instagram.com/graphql/query/";
    $headers = array(
        'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36'
    );
    $variables = '{"child_comment_count":3,"fetch_comment_count":40,"has_threaded_comments":true,"parent_comment_count":24,"shortcode":"' . $reel_id . '"}';
    $params = array(
        'hl' => 'en',
        'query_hash' => 'b3055c01b4b222b8a47dc12b090e4e64',
        'variables' => $variables
    );
    $url = $link . '?' . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    try {
        $resp = curl_exec($ch);
        $data = json_decode($resp, true);
        $video_link = $data['data']['shortcode_media']['video_url'];
        $image_preview = $data['data']['shortcode_media']['display_url'];

        return array($video_link, $image_preview);
    } catch (Exception $e) {
        return array("", "");
    } finally {
        curl_close($ch);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Instagram Reels Downloader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Gotu&display=swap" rel="stylesheet">
    <style type="text/css">
        html,
        body {
            font-family: "Gotu"
        }

        input {
            padding: 5px;
            border-radius: 10px;
            border-style: solid;
            border-color: blue;
            transition-duration: 0.5s;
            width: 80%;
        }

        input:focus {
            border-color: skyblue;
            transition-duration: 0.5s;
        }
    </style>
</head>

<body class="bg-light">
    <div class="text-center p-5">
        <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMzIuMDA0IiBoZWlnaHQ9IjEzMiIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgoJPGRlZnM+CgkJPGxpbmVhckdyYWRpZW50IGlkPSJiIj4KCQkJPHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjMzc3MWM4Ii8+CgkJCTxzdG9wIHN0b3AtY29sb3I9IiMzNzcxYzgiIG9mZnNldD0iLjEyOCIvPgoJCQk8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM2MGYiIHN0b3Atb3BhY2l0eT0iMCIvPgoJCTwvbGluZWFyR3JhZGllbnQ+CgkJPGxpbmVhckdyYWRpZW50IGlkPSJhIj4KCQkJPHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjZmQ1Ii8+CgkJCTxzdG9wIG9mZnNldD0iLjEiIHN0b3AtY29sb3I9IiNmZDUiLz4KCQkJPHN0b3Agb2Zmc2V0PSIuNSIgc3RvcC1jb2xvcj0iI2ZmNTQzZSIvPgoJCQk8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNjODM3YWIiLz4KCQk8L2xpbmVhckdyYWRpZW50PgoJCTxyYWRpYWxHcmFkaWVudCBpZD0iYyIgY3g9IjE1OC40MjkiIGN5PSI1NzguMDg4IiByPSI2NSIgeGxpbms6aHJlZj0iI2EiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiBncmFkaWVudFRyYW5zZm9ybT0ibWF0cml4KDAgLTEuOTgxOTggMS44NDM5IDAgLTEwMzEuNDAyIDQ1NC4wMDQpIiBmeD0iMTU4LjQyOSIgZnk9IjU3OC4wODgiLz4KCQk8cmFkaWFsR3JhZGllbnQgaWQ9ImQiIGN4PSIxNDcuNjk0IiBjeT0iNDczLjQ1NSIgcj0iNjUiIHhsaW5rOmhyZWY9IiNiIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgZ3JhZGllbnRUcmFuc2Zvcm09Im1hdHJpeCguMTczOTQgLjg2ODcyIC0zLjU4MTggLjcxNzE4IDE2NDguMzQ4IC00NTguNDkzKSIgZng9IjE0Ny42OTQiIGZ5PSI0NzMuNDU1Ii8+Cgk8L2RlZnM+Cgk8cGF0aCBmaWxsPSJ1cmwoI2MpIiBkPSJNNjUuMDMgMEMzNy44ODggMCAyOS45NS4wMjggMjguNDA3LjE1NmMtNS41Ny40NjMtOS4wMzYgMS4zNC0xMi44MTIgMy4yMi0yLjkxIDEuNDQ1LTUuMjA1IDMuMTItNy40NyA1LjQ2OEM0IDEzLjEyNiAxLjUgMTguMzk0LjU5NSAyNC42NTZjLS40NCAzLjA0LS41NjggMy42Ni0uNTk0IDE5LjE4OC0uMDEgNS4xNzYgMCAxMS45ODggMCAyMS4xMjUgMCAyNy4xMi4wMyAzNS4wNS4xNiAzNi41OS40NSA1LjQyIDEuMyA4LjgzIDMuMSAxMi41NiAzLjQ0IDcuMTQgMTAuMDEgMTIuNSAxNy43NSAxNC41IDIuNjguNjkgNS42NCAxLjA3IDkuNDQgMS4yNSAxLjYxLjA3IDE4LjAyLjEyIDM0LjQ0LjEyIDE2LjQyIDAgMzIuODQtLjAyIDM0LjQxLS4xIDQuNC0uMjA3IDYuOTU1LS41NSA5Ljc4LTEuMjggNy43OS0yLjAxIDE0LjI0LTcuMjkgMTcuNzUtMTQuNTMgMS43NjUtMy42NCAyLjY2LTcuMTggMy4wNjUtMTIuMzE3LjA4OC0xLjEyLjEyNS0xOC45NzcuMTI1LTM2LjgxIDAtMTcuODM2LS4wNC0zNS42Ni0uMTI4LTM2Ljc4LS40MS01LjIyLTEuMzA1LTguNzMtMy4xMjctMTIuNDQtMS40OTUtMy4wMzctMy4xNTUtNS4zMDUtNS41NjUtNy42MjRDMTE2LjkgNCAxMTEuNjQgMS41IDEwNS4zNzIuNTk2IDEwMi4zMzUuMTU3IDEwMS43My4wMjcgODYuMTkgMEg2NS4wM3oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDEuMDA0IDEpIi8+Cgk8cGF0aCBmaWxsPSJ1cmwoI2QpIiBkPSJNNjUuMDMgMEMzNy44ODggMCAyOS45NS4wMjggMjguNDA3LjE1NmMtNS41Ny40NjMtOS4wMzYgMS4zNC0xMi44MTIgMy4yMi0yLjkxIDEuNDQ1LTUuMjA1IDMuMTItNy40NyA1LjQ2OEM0IDEzLjEyNiAxLjUgMTguMzk0LjU5NSAyNC42NTZjLS40NCAzLjA0LS41NjggMy42Ni0uNTk0IDE5LjE4OC0uMDEgNS4xNzYgMCAxMS45ODggMCAyMS4xMjUgMCAyNy4xMi4wMyAzNS4wNS4xNiAzNi41OS40NSA1LjQyIDEuMyA4LjgzIDMuMSAxMi41NiAzLjQ0IDcuMTQgMTAuMDEgMTIuNSAxNy43NSAxNC41IDIuNjguNjkgNS42NCAxLjA3IDkuNDQgMS4yNSAxLjYxLjA3IDE4LjAyLjEyIDM0LjQ0LjEyIDE2LjQyIDAgMzIuODQtLjAyIDM0LjQxLS4xIDQuNC0uMjA3IDYuOTU1LS41NSA5Ljc4LTEuMjggNy43OS0yLjAxIDE0LjI0LTcuMjkgMTcuNzUtMTQuNTMgMS43NjUtMy42NCAyLjY2LTcuMTggMy4wNjUtMTIuMzE3LjA4OC0xLjEyLjEyNS0xOC45NzcuMTI1LTM2LjgxIDAtMTcuODM2LS4wNC0zNS42Ni0uMTI4LTM2Ljc4LS40MS01LjIyLTEuMzA1LTguNzMtMy4xMjctMTIuNDQtMS40OTUtMy4wMzctMy4xNTUtNS4zMDUtNS41NjUtNy42MjRDMTE2LjkgNCAxMTEuNjQgMS41IDEwNS4zNzIuNTk2IDEwMi4zMzUuMTU3IDEwMS43My4wMjcgODYuMTkgMEg2NS4wM3oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDEuMDA0IDEpIi8+Cgk8cGF0aCBmaWxsPSIjZmZmIiBkPSJNNjYuMDA0IDE4Yy0xMy4wMzYgMC0xNC42NzIuMDU3LTE5Ljc5Mi4yOS01LjExLjIzNC04LjU5OCAxLjA0My0xMS42NSAyLjIzLTMuMTU3IDEuMjI2LTUuODM1IDIuODY2LTguNTAzIDUuNTM1LTIuNjcgMi42NjgtNC4zMSA1LjM0Ni01LjU0IDguNTAyLTEuMTkgMy4wNTMtMiA2LjU0Mi0yLjIzIDExLjY1QzE4LjA2IDUxLjMyNyAxOCA1Mi45NjQgMTggNjZzLjA1OCAxNC42NjcuMjkgMTkuNzg3Yy4yMzUgNS4xMSAxLjA0NCA4LjU5OCAyLjIzIDExLjY1IDEuMjI3IDMuMTU3IDIuODY3IDUuODM1IDUuNTM2IDguNTAzIDIuNjY3IDIuNjcgNS4zNDUgNC4zMTQgOC41IDUuNTQgMy4wNTQgMS4xODcgNi41NDMgMS45OTYgMTEuNjUyIDIuMjMgNS4xMi4yMzMgNi43NTUuMjkgMTkuNzkuMjkgMTMuMDM3IDAgMTQuNjY4LS4wNTcgMTkuNzg4LS4yOSA1LjExLS4yMzQgOC42MDItMS4wNDMgMTEuNjU2LTIuMjMgMy4xNTYtMS4yMjYgNS44My0yLjg3IDguNDk3LTUuNTQgMi42Ny0yLjY2OCA0LjMxLTUuMzQ2IDUuNTQtOC41MDIgMS4xOC0zLjA1MyAxLjk5LTYuNTQyIDIuMjMtMTEuNjUuMjMtNS4xMi4yOS02Ljc1Mi4yOS0xOS43ODggMC0xMy4wMzYtLjA2LTE0LjY3Mi0uMjktMTkuNzkyLS4yNC01LjExLTEuMDUtOC41OTgtMi4yMy0xMS42NS0xLjIzLTMuMTU3LTIuODctNS44MzUtNS41NC04LjUwMy0yLjY3LTIuNjctNS4zNC00LjMxLTguNS01LjUzNS0zLjA2LTEuMTg3LTYuNTUtMS45OTYtMTEuNjYtMi4yMy01LjEyLS4yMzMtNi43NS0uMjktMTkuNzktLjI5em0tNC4zMDYgOC42NWMxLjI3OC0uMDAyIDIuNzA0IDAgNC4zMDYgMCAxMi44MTYgMCAxNC4zMzUuMDQ2IDE5LjM5Ni4yNzYgNC42OC4yMTQgNy4yMi45OTYgOC45MTIgMS42NTMgMi4yNC44NyAzLjgzNyAxLjkxIDUuNTE2IDMuNTkgMS42OCAxLjY4IDIuNzIgMy4yOCAzLjU5MiA1LjUyLjY1NyAxLjY5IDEuNDQgNC4yMyAxLjY1MyA4LjkxLjIzIDUuMDYuMjggNi41OC4yOCAxOS4zOXMtLjA1IDE0LjMzLS4yOCAxOS4zOWMtLjIxNCA0LjY4LS45OTYgNy4yMi0xLjY1MyA4LjkxLS44NyAyLjI0LTEuOTEyIDMuODM1LTMuNTkyIDUuNTE0LTEuNjggMS42OC0zLjI3NSAyLjcyLTUuNTE2IDMuNTktMS42OS42Ni00LjIzMiAxLjQ0LTguOTEyIDEuNjU0LTUuMDYuMjMtNi41OC4yOC0xOS4zOTYuMjgtMTIuODE3IDAtMTQuMzM2LS4wNS0xOS4zOTYtLjI4LTQuNjgtLjIxNi03LjIyLS45OTgtOC45MTMtMS42NTUtMi4yNC0uODctMy44NC0xLjkxLTUuNTItMy41OS0xLjY4LTEuNjgtMi43Mi0zLjI3Ni0zLjU5Mi01LjUxNy0uNjU3LTEuNjktMS40NC00LjIzLTEuNjUzLTguOTEtLjIzLTUuMDYtLjI3Ni02LjU4LS4yNzYtMTkuMzk4cy4wNDYtMTQuMzMuMjc2LTE5LjM5Yy4yMTQtNC42OC45OTYtNy4yMiAxLjY1My04LjkxMi44Ny0yLjI0IDEuOTEyLTMuODQgMy41OTItNS41MiAxLjY4LTEuNjggMy4yOC0yLjcyIDUuNTItMy41OTIgMS42OTItLjY2IDQuMjMzLTEuNDQgOC45MTMtMS42NTUgNC40MjgtLjIgNi4xNDQtLjI2IDE1LjA5LS4yN3ptMjkuOTI4IDcuOTdjLTMuMTggMC01Ljc2IDIuNTc3LTUuNzYgNS43NTggMCAzLjE4IDIuNTggNS43NiA1Ljc2IDUuNzYgMy4xOCAwIDUuNzYtMi41OCA1Ljc2LTUuNzYgMC0zLjE4LTIuNTgtNS43Ni01Ljc2LTUuNzZ6bS0yNS42MjIgNi43M2MtMTMuNjEzIDAtMjQuNjUgMTEuMDM3LTI0LjY1IDI0LjY1IDAgMTMuNjEzIDExLjAzNyAyNC42NDUgMjQuNjUgMjQuNjQ1Qzc5LjYxNyA5MC42NDUgOTAuNjUgNzkuNjEzIDkwLjY1IDY2Uzc5LjYxNiA0MS4zNSA2Ni4wMDMgNDEuMzV6bTAgOC42NWM4LjgzNiAwIDE2IDcuMTYzIDE2IDE2IDAgOC44MzYtNy4xNjQgMTYtMTYgMTYtOC44MzcgMC0xNi03LjE2NC0xNi0xNiAwLTguODM3IDcuMTYzLTE2IDE2LTE2eiIvPgo8L3N2Zz4=">
        <h1 class="mt-5">Intagram Reels Downloader</h1>

    </div>
    <div class="text-center">
        Paste a video url below and press "Download". Now scroll down to "Download Video" button and press to initiate the download process.<br><br>
        <form method="POST" class="mt-2">
            <input type="text" placeholder="https://www.instagram.com/reel/abcdef1234/?hl=en" class="mb-3" name="insta-url"><br><br>
            <button class="btn btn-success" type="submit">Download</button>
        </form>
    </div>


    <?php
    if (isset($_POST['insta-url']) && !empty($_POST['insta-url'])) {
        $url = trim($_POST['insta-url']);
        $response_array = getVideoLink($url);
        $embed_link = $response_array[0];
        $image_preview = $response_array[1];
    ?>
        <div class="border m-3 mb-5" id="result">
            <div class="row m-0 p-2">
                <div class="col-sm-5 col-md-5 col-lg-5 text-center"><img width="250px" height="250px" src="<?php echo $image_preview; ?>"></div>
                <div class="col-sm-6 col-md-6 col-lg-6 text-center mt-5">
                    <ul style="list-style: none;padding: 0px">
                        <li>
                            <button onclick="window.open('<?php echo $embed_link; ?>', '_blank')" class="btn btn-success" type='button' name='download'>Download Video</button>
                        </li>
                        <li>
                            <div class="alert alert-primary mb-0 mt-3">If the video opens directly, try saving it by pressing CTRL+S or on phone, save from three dots in the bottom left corner</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="m-5">
        &nbsp;
    </div>
    <div class="bg-dark text-white" style="position: fixed; bottom: 0;width: 100%;padding:15px">Developed by <a target="_blank" href="https://www.github.com/TufayelLUS">Tufayel Ahmed</a> <span style="float: right;">Copyright &copy; <?php echo date("Y"); ?></span></div>
    <script type="text/javascript">
        window.setInterval(function() {
            if ($("input[name='insta-url']").attr("placeholder") == "https://www.instagram.com/reel/CvP2R_RMPsP/?hl=en") {
                $("input[name='insta-url']").attr("placeholder", "https://www.instagram.com/reel/Cu9xY6BRv6x/?hl=en");
            } else {
                $("input[name='insta-url']").attr("placeholder", "https://www.instagram.com/reel/CvP2R_RMPsP/?hl=en");
            }
        }, 3000);
    </script>
</body>

</html>
