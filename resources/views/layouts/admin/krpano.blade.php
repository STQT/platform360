<!DOCTYPE html>
<html>
<head>
    <title>Uzbekistan360 admin</title>
    
    <link href="/css/app.css" rel="stylesheet">
    <style>
        ::-webkit-input-placeholder {
            color: #666;
        }

        :-ms-input-placeholder {
            color: #666;
        }

        ::-ms-input-placeholder {
            color: #666;
        }

        ::placeholder {
            color: #666;
        }

        /*::-moz-selection {
            background-color: #007c82;
            color: #fff;
        }

        ::selection {
            background-color: #007c82;
            color: #fff;
        }
        */
        * {
            outline: none;
        }

        *:hover,
        *:focus {
            outline: none !important;
        }

        html {
            font-size: 16px;
            height: 100%;
            line-height: 1.3;
        }

        @media (max-width: 575.98px) {
            html {
                font-size: 14px;
            }
        }

        body {
            font-family: "roboto", sans-serif;
            height: 100%;
            color: #000;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
        }

        h1,
        h2 {
            letter-spacing: 2px;
        }

        ul,
        li {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
        }

        a {
            color: #000;
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
            color: #000;
        }

        audio,
        canvas,
        iframe,
        img,
        svg,
        video {
            vertical-align: middle;
        }

        textarea {
            resize: none;
        }

        section {
            position: relative;
        }

        input,
        select,
        button,
        textarea {
            outline: none;
            border: none;
        }

        button:hover {
            cursor: pointer;
        }

        *,
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        @-ms-viewport {
            width: device-width;
        }

        @media only screen and (min-device-width: 800px) {
            html {
                overflow: hidden;
            }
        }

        html {
            height: 100%;
        }

        body {
            height: 100%;
            overflow: hidden;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #FFFFFF;
            background-color: #000000;
        }

        #adminbackurl {
            position: absolute;
            top: 25px;
            background: rgba(0,0,0,0.5);
            left: 25px;
            color: #fff;
            border: none;
            padding: 10px 25px;
            cursor: pointer;
        }
        #addHotspot {
            position: absolute;
            top: 25px;
            background: rgba(0,0,0,0.5);
            left: 150px;
            color: #fff;
            border: none;
            padding: 10px 25px;
            cursor: pointer;
        }

        #addVideo {
            position: absolute;
            top: 25px;
            background: rgba(0,0,0,0.5);
            left: 320px;
            color: #fff;
            border: none;
            padding: 10px 25px;
            cursor: pointer;
        }
        #adminbackurl:hover {
            background: rgba(0,0,0,1);
        }
        #addHotspot:hover {
            background: rgba(0,0,0,1);
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            justify-content: center;
            -ms-align-items: center;
            align-items: center;
        }

        .modal-close {
            position: absolute;
            top: 6px;
            right: 10px;
            color: #000;
            cursor: pointer;
            line-height: 1;
            font-size: 22px;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,.5);
        }

        .modal-wrap {
            position: relative;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            padding: 30px;
            background: #fff;
            width: 75%;
            height: 80%;
        }

        .categories {
            width: 30%;
            border: 1px solid #000;
            margin-right: 20px;
            padding: 20px;
            overflow: auto;
        }

        .category-list {
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            -webkit-flex-direction: column;
            -moz-flex-direction: column;
            -ms-flex-direction: column;
            -o-flex-direction: column;
            flex-direction: column;
        }

        .category-list li,
        .info-list li {
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        .category-list li:last-child,
        .info-list li:last-child {
            margin-bottom: 0px;
        }

        .category-list a,
        .info-list a {
            padding: 10px;
            /*text-align: center;*/
            display: block;
        }

        .category-list a:hover,
        .info-list a:hover {
            background: #eee;
        }

        .cotegory-info {
            width: 70%;
            border: 1px solid #000;
            padding: 20px;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            -webkit-flex-direction: column;
            -moz-flex-direction: column;
            -ms-flex-direction: column;
            -o-flex-direction: column;
            flex-direction: column;
            position: relative;
        }

        .mess {
            font-size: 18px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            color: #000;
            text-align: center;
        }

        .info-search {
            padding: 10px;
            border: 1px solid #000;
            margin-bottom: 20px;
            width: 100%;
        }

        .info-list {
            overflow: auto;
        }

        .info-list a {
            padding: 5px;
        }

        .info-pagination {
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .info-pagination li {
            margin: 0 10px;
            display: block;
        }

        .info-pagination a {
            display: block;
        }

        #videoModal {
            color: #000;
        }
        #videoModal input[type=text] {
            border: 1px solid black;
        }
    </style>

</head>

<div id="pano" style="width:100%;height:100%;"></div>


<body>

@yield('content')

<script src="/krpano.js"></script>

@yield('scripts')

</body>
</html>
