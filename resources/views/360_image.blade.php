<html>
<head>
    <style>
        .a-enter-vr {
            display: none !important;
        }
    </style>
    <script src="https://aframe.io/releases/1.2.0/aframe.min.js"></script>
</head>
<body>
<a-scene>
    <a-assets>
        <img id="panorama" src="{{$link}}" crossorigin/>
    </a-assets>
    <a-sky src="#panorama" >
        <a-animation attribute="rotation" fill="forwards" easing="linear" dur="15000" from="0 0 0" to="0 360 0" repeat="indefinite"></a-animation>
    </a-sky>
</a-scene>

</body>
</html>
