<?php
namespace App\Common;

class MailRenderer
{
    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * MailRenderer constructor.
     *
     * @param string $templatePath
     * @param array $attributes
     */
    public function __construct($templatePath = '', $attributes = [])
    {
        $this->templatePath = rtrim($templatePath, '/\\').'/';
        $this->attributes = $attributes;
    }

    /**
     * Render a template
     * @param string $template
     * @param array $data
     *
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    public function render($template, array $data = [])
    {
        if (isset($data['template'])) {
            throw new \InvalidArgumentException('Duplicate template key found');
        }

        if (!is_file($this->templatePath.$template)) {
            throw new \RuntimeException(sprintf('View cannot render template `%s` because it does not exist', $template));
        }

        $data = array_merge($this->attributes, $data);

        try {
            ob_start();
            $this->protectedIncludeScope($this->templatePath.$template, $data);
            $output = ob_get_clean();
        } catch (\Throwable $e) {
            // PHP 7+
            ob_end_clean();
            throw $e;
        } catch (\Exception $e) {
            // PHP < 7
            ob_end_clean();
            throw $e;
        }

        return $output;
    }

    /**
     * @param string $template
     * @param array $data
     */
    protected function protectedIncludeScope ($template, array $data)
    {
        extract($data);
        include $template;
    }
}
