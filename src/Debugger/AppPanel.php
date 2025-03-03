<?php
declare(strict_types=1);

namespace App\Debugger;


class AppPanel extends BasePanel {


  public function getTabName(): string {
    return "APP";
  }

  public function getTabIcon(): ?string {
    return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAUZJREFUeNp0U8FRwzAQtD35x3SQEkQFcSrANBCsCkh+/MiTn3EFcVJBqICkA9GBOyCpAHaZFaOR5Zu5Odlzu3fa0+VZZOu3Y4lQw5fwhX4P8Av8dHxZX8P8PALvEJ7hZZY2gjuQ7EYEAO8RGp+kqvsJoh4klodC4FZt9yLI1AltBbfyO31XwmQ5DhXiE/wDbuCvQaUzKq1SLQC3QXDs4AG+DcQKzSGxSRGA+J3YQt+lKsd3pvItSD4ntLiRoBLQJIQ6Id77a3DEat2P280EvCbYO7U6BAB2YnSmbpYi/kjhQQneWP1LnXiS7+iN/Ino+BNJZ8THQMhauiyCRzZ6YIVapVCN7nyYEGyeumYBUK8u/PyXUZLRW6njERPrx0gNSs28ihIpVhsslt8JG++CmRhnbOzWorobbaOIGu2BSQA7XfnffgUYAAq9euKiVuptAAAAAElFTkSuQmCC";
  }

  public function getTabContent(): string {
    $table = $this->createTableToggle("Constants", [
      ["ROOT_DIR", constant("ROOT_DIR")],
      ["CONFIG_DIR", constant("CONFIG_DIR")],
      ["PUBLIC_DIR", constant("PUBLIC_DIR")],
      ["SRC_DIR", constant("SRC_DIR")],
      ["TMP_DIR", constant("TMP_DIR")],
      ["TEMPLATE_DIR", constant("TEMPLATE_DIR")],
    ]);

    return $table;
  }


}

