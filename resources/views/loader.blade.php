<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    body {
  background: #0d8aa5;
}

.loading {
  position: absolute;
  left: 50%;
  top: 50%;
  margin: -60px 0 0 -60px;
  background: #fff;
  width: 100px;
  height: 100px;
  border-radius: 100%;
  border: 10px solid #19bee1;
}
.loading:after {
  content: '';
  background: trasparent;
  width: 140%;
  height: 140%;
  position: absolute;
  border-radius: 100%;
  top: -20%;
  left: -20%;
  opacity: 0.7;
  box-shadow: rgba(255, 255, 255, 0.6) -4px -5px 3px -3px;
  animation: rotate 2s infinite linear;
}

@keyframes rotate {
  0% {
    transform: rotateZ(0deg);
  }
  100% {
    transform: rotateZ(360deg);
  }
}
</style>

<body>
    <div class="loading"></div>
    <div class="logo">

    </div>
</body>
</html>
