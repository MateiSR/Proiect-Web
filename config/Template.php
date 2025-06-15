<?php

class Template
{
  protected $template_file;
  protected $data = [];

  public function __construct(string $template_path)
  {
    $this->template_file = __DIR__ . '/../' . $template_path;
  }

  public function __set(string $key, $value)
  {
    $this->data[$key] = $value;
  }

  public function __get(string $key)
  {
    return $this->data[$key] ?? null;
  }

  public function render(): string
  {
    if (file_exists($this->template_file)) {
      extract($this->data);
      ob_start();
      include $this->template_file;
      return ob_get_clean();
    } else {
      throw new Exception("Template file not found: {$this->template_file}");
    }
  }
}