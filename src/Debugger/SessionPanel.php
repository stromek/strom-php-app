<?php
declare(strict_types=1);

namespace App\Debugger;


use App\Http\Session\Session;


class SessionPanel extends BasePanel {

  public function getTabName(): string {
    return "Session";
  }

  public function getTabIcon(): string {
    return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9kLAQgrK+JW5KIAAAKOSURBVDjLpZNLSFRxFMa////eO3cePnLG0bHGYCiCkJokCMNApFwJGWiEkQVGouMiKcGFRkRILXpHtlDSfEToJjDIjF7YIgwfQU1o4GMTmuOrud57577+LSKpyYToW57z8eOc83EIYwz/I36tYnXQe26rx6qj1HLpFsO3GJUnJP565/uvDfFeEj9BVdBTF/SyS0IiIRYDYgaDpFiYkxkbWxYuPg5Hzv/qp/HEYKp52eEiZHHZxHzUhKQCFrWBpzxxC0ZNvP8PwHLUhK8ghNLT9cgvOYkZlceCzkO2bYBTpI7O2oPiujfwBDwLecVlbm3sBTIFAeVVISS5nEjeWYCnFw4rWdsy2boAxXJ9UiaHcg2HFykJBlLSMiCZPBAZh2Q5P++ouKP9dQXfkQfC7fn8if62a0gyl0B4EYQXkUA1vGq/gltf9k2nFbU41kwhreShaBGr3IH0pr6bOQjfOIqfPUIIMk51obTuHWRjqoYwvTnSWyGvAlJLuokGnHCL/tYnV7MxytsxLAGyBGgKYChAwElQkKygtGEEEWk8xFO1eam30uABwOeQizLMudbK2mIMRDl8lBk0HTAVBl37Afgwx6B77Cgvy8Hr+wNNs+AUAG2EMYaRs9sN/95DXI+ci6FAISgAZjAYGmDogKoCukawIhPkxZ6j2j+A8b5uPedu2EYBgLMI5UUn3gxGsIUqUGVAXQFiMkFshYBSCs5GsMev4u3IDCjHg6OEX02BZh2on+zvMOyxGeNR1yDERAa7G3ClMyRnMiT5LLg3WuhpeYYACcuTL3tijuz9jb+lMNUeSrw3tsnWMb2rkS3OF3KCww1QAQRgmm5YhioJHs9ooXf42Jnds8rm403RNZ/pX/UdGBsfZjGavccAAAAASUVORK5CYII=";
  }

  public function getTabContent(): ?string {
    if(!Session::isActive()) {
      return null;
    }

    $prefix = "<?php";
    $code = str_replace(htmlentities($prefix), "", highlight_string($prefix." ".var_export($_SESSION, true), true));

    return $this->createCode($code);
  }

}
