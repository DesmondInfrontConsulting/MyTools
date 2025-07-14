<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MyKadGenerator extends Component
{
    use WithFileUploads;

    public $photo;
    public $name;
    public $id_number;
    public $address;
    public $passport;
    public $ic_back;
    public $downloadUrl;
    public $template = 'template2.jpg';

    protected $templateConfig = [
        'template.png' => [
            'font_sizes' => [
                'ic_number' => 20,
                'name' => 20,
                'address' => 16,
                'ic_back' => 22,
            ],
            'bg_colors' => [
                'ic_number' => '#6c878f',
                'name' => '#6c878f',
                'address' => '#6c878f',
                'ic_back' => '#4e6e83',
            ],
            'block_sizes' => [
                'ic_number' => ['width' => 247, 'height' => 26],
                'name' => ['width' => 245, 'height' => 28],
                'address' => ['width' => 245, 'height' => 99],
                'ic_back' => ['width' => 247, 'height' => 28],
            ],
            'photo' => ['x' => 413, 'y' => 97, 'width' => 172, 'height' => 228],
            'faded_photo' => ['x' => 312, 'y' => 97, 'width' => 95, 'height' => 126],
            'ic_number' => ['x' => 36, 'y' => 99],
            'ic_text' => ['x' => 45, 'y' => 112],
            'name' => [
                'x' => 46,
                'y' => 249,
                'text_x' => 51,
                'text_y' => 268,
            ],
            'address' => [
                'x' => 46,
                'y' => 280,
                'text_x' => 56,
                'text_y' => 298,
            ],
            'ic_back' => [
                'x' => 193,
                'y' => 744,
                'text_x' => 200,
                'text_y' => 758,
            ],
        ],
        'template2.jpg' => [
            'font_sizes' => [
                'ic_number' => 36,
                'name' => 30,
                'address' => 22,
                'ic_back' => 36,
            ],
            'bg_colors' => [
                'ic_number' => '#c4dcde',
                'name' => '#addaf9',
                'address' => '#addaf9',
                'ic_back' => '#c8e9fc',
            ],
            'block_sizes' => [
                'ic_number' => ['width' => 260, 'height' => 40],
                'name' => ['width' => 250, 'height' => 40],
                'address' => ['width' => 400, 'height' => 120],
                'ic_back' => ['width' => 360, 'height' => 40],
            ],
            'photo' => ['x' => 572, 'y' => 180, 'width' => 221, 'height' => 286],
            'faded_photo' => ['x' => 425, 'y' => 178, 'width' => 101, 'height' => 126],
            'ic_number' => ['x' => 57, 'y' => 147],
            'ic_text' => ['x' => 60, 'y' => 170],
            'name' => ['x' => 50, 'y' => 365, 'text_x' => 55, 'text_y' => 390],
            'address' => ['x' => 50, 'y' => 431, 'text_x' => 60, 'text_y' => 450],
            'ic_back' => ['x' => 251, 'y' => 1009, 'text_x' => 260, 'text_y' => 1030],
        ],
    ];

    public function updateTemplate()
    {
        $this->template;
    }

    public function generate()
    {
        try {
            $this->validateInputs();

            $fontPath = public_path('fonts/arial.ttf');
            $manager = new ImageManager(new Driver());

            $templatePath = public_path($this->template);
            $template = $manager->read($templatePath);

            $this->addUserPhoto($manager, $template);
            $this->addFadedPhoto($manager, $template);
            $this->addICNumber($manager, $template, $fontPath);
            $this->addNameBlock($manager, $template, $fontPath);
            $this->addAddressBlock($manager, $template, $fontPath);
            $this->addICBackField($manager, $template, $fontPath);

            // Ensure folder exists
            $outputPath = storage_path('app/public/generated');
            if (!file_exists($outputPath)) {
                mkdir($outputPath, 0755, true);
            }

            $this->previewImage($template); // Continue with your logic
        } catch (\Exception $e) {
            $this->dispatch('hide-modal');
        } finally {
            $this->dispatch('hide-modal');
        }
    }


    protected function validateInputs()
    {
        $this->validate([
            'photo'     => 'required|image|max:2048',
            'ic_back'   => 'nullable|string|max:50',
            'name'      => 'required|string|max:255',
            'id_number' => 'required|string|max:20',
            'address'   => 'required|string|max:255',
        ]);
    }

    protected function addUserPhoto($manager, &$template)
    {
        $config = $this->templateConfig[$this->template]['photo'];
        $photo = $manager->read($this->photo->getRealPath())
            ->resize($config['width'], $config['height']);
        $template->place($photo, 'top-left', $config['x'], $config['y']);
    }

    protected function addFadedPhoto($manager, &$template)
    {
        $config = $this->templateConfig[$this->template]['faded_photo'];
        $faded = $manager->read($this->photo->getRealPath())
            ->resize($config['width'], $config['height']);
        $template->place($faded, 'top-left', $config['x'], $config['y'], 70);
    }

    protected function addICNumber($manager, &$template, $fontPath)
    {
        $cfg = $this->templateConfig[$this->template];
        $block = $cfg['ic_number'];
        $text = $cfg['ic_text'];
        $fontSize = $cfg['font_sizes']['ic_number'];
        $bgColor = $cfg['bg_colors']['ic_number'];
        $size = $cfg['block_sizes']['ic_number'];

        $template->place($manager->create($size['width'], $size['height'])->fill($bgColor), 'top-left', $block['x'], $block['y']);
        $template->text(
            $this->id_number,
            $text['x'],
            $text['y'],
            fn($font) =>
            $font->filename($fontPath)->size($fontSize)->color('#000000')->align('left')->valign('middle')
        );
    }

    protected function addNameBlock($manager, &$template, $fontPath)
    {
        $cfg = $this->templateConfig[$this->template];
        $coords = $cfg['name'];
        $fontSize = $cfg['font_sizes']['name'];
        $bgColor = $cfg['bg_colors']['name'];
        $size = $cfg['block_sizes']['name'];

        $textX = $coords['text_x'] ?? $coords['x'] + 5;
        $textY = $coords['text_y'] ?? $coords['y'] + 20;

        $template->place($manager->create($size['width'], $size['height'])->fill($bgColor), 'top-left', $coords['x'], $coords['y']);
        $template->text(
            strtoupper($this->name),
            $textX,
            $textY,
            fn($font) =>
            $font->filename($fontPath)->size($fontSize)->color('#000000')
        );
    }

    protected function addAddressBlock($manager, &$template, $fontPath)
    {
        $cfg = $this->templateConfig[$this->template];
        $coords = $cfg['address'];
        $fontSize = $cfg['font_sizes']['address'];
        $bgColor = $cfg['bg_colors']['address'];
        $size = $cfg['block_sizes']['address'];

        $wrapped = wordwrap(strtoupper($this->address), 35, "\n");
        $lines = explode("\n", $wrapped);

        $template->place($manager->create($size['width'], $size['height'])->fill($bgColor), 'top-left', $coords['x'], $coords['y']);

        $textX = $coords['text_x'] ?? $coords['x'] + 10;
        $textY = $coords['text_y'] ?? $coords['y'] + 15;
        $lineHeight = $fontSize + 2;

        foreach ($lines as $i => $line) {
            $template->text(
                $line,
                $textX,
                $textY + ($i * $lineHeight),
                fn($font) =>
                $font->filename($fontPath)->size($fontSize)->color('#000000')
            );
        }
    }

    protected function addICBackField($manager, &$template, $fontPath)
    {
        $cfg = $this->templateConfig[$this->template];
        $coords = $cfg['ic_back'];
        $fontSize = $cfg['font_sizes']['ic_back'];
        $bgColor = $cfg['bg_colors']['ic_back'];
        $size = $cfg['block_sizes']['ic_back'];

        $this->ic_back = $this->id_number . "-11-12";

        $template->place($manager->create($size['width'], $size['height'])->fill($bgColor), 'top-left', $coords['x'], $coords['y']);
        $template->text(
            $this->ic_back,
            $coords['text_x'],
            $coords['text_y'],
            fn($font) =>
            $font->filename($fontPath)->size($fontSize)->color('#000000')->align('left')->valign('middle')
        );
    }


    protected function previewImage($template)
    {
        $this->downloadUrl = 'data:image/png;base64,' . base64_encode($template->toJpeg());
    }

    public function render()
    {
        return view('livewire.my-kad-generator');
    }
}
