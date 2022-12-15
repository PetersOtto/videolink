<?php
// Videolink extension, https://github.com/PetersOtto/yellow-videolink

class YellowVideolink {
    const VERSION = "0.0.1";
    public $yellow;         // access to API

    // Handle initialisation
    public function onLoad($yellow) {
        $this->yellow = $yellow;
    }

    // Handle page content of shortcut
    public function onParseContentShortcut($page, $name, $text, $type) {
        $output = null;
        if ($name=="videolink" && ($type=="block" || $type=="inline")) {
            list($name, $videolink, $alt, $styleSize, $stylePosition) = $this->yellow->toolbox->getTextArguments($text);
            if (is_string_empty($alt)) $alt = $this->yellow->language->getText("imageDefaultAlt");
            $width = "100%";
            $width = str_replace("%", "", $width);
            $height = $width;
            $path = $this->yellow->lookup->findMediaDirectory("coreImageLocation");
            if ($this->yellow->extension->isExisting("picture")) {
                $width = ($width / 2) . "%";
                $height = ($height / 2) . "%";
            }
            list($src, $width, $height) = $this->yellow->extension->get("image")->getImageInformation($path.$name, $width, $height);
            $baseURL = $this->yellow->system->get("coreServerBase");
            $output = "<div class=\"vl-wrapper\"";
            if (!is_string_empty($stylePosition)) $output .= " class=\"vl-wrapper ".htmlspecialchars($stylePosition)."\"";
            $output .= "> \n";
            $output .= "<div class=\"vl-video\"";
            if (!is_string_empty($styleSize)) $output .= " class=\"vl-video ".htmlspecialchars($styleSize)."\"";
            $output .= "> \n";
            $output .=" <div class=\"vl-image\">\n";
            $output .= "<img src=\"".htmlspecialchars($src)."\"";
            if ($width && $height) $output .= " width=\"".htmlspecialchars($width)."\" height=\"".htmlspecialchars($height)."\"";
            if (!is_string_empty($alt)) $output .= " alt=\"".htmlspecialchars($alt)."\" title=\"".htmlspecialchars($alt)."\"";
            $output .= " /> \n";
            $output .=" </div> \n";
            $output .=" <div class=\"vl-overlay-image\"> <a href=\"" .htmlspecialchars($videolink). "\" target=\"_blank\" rel=\"noopener noreferrer\"><img src=\"media/extensions/videolink.svg\"" ;
            if (!is_string_empty($alt)) $output .= " alt=\"Start Video - ".htmlspecialchars($alt)."\" title=\"Start Video - ".htmlspecialchars($alt)."\"";
            $output .= " ></a></div>\n";
            $output .=" </div>";
            $output .=" </div> \n";
        }
        return $output;
    }

    // Handle page extra data
    public function onParsePageExtra($page, $name) {
        $output = null;
        if ($name=="header") {
            $extensionLocation = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("coreExtensionLocation");
            $output = "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$extensionLocation}videolink.css\" />\n";
        }
        return $output;
    }
}
