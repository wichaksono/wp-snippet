<?php

class ChildThemeGenerator 
{
    private $parentTheme;
    private $childThemeName;
    private $childThemeSlug;
    private $themesPath;
    
    public function __construct($parentTheme, $childThemeName, $themesPath = null) 
    {
        $this->parentTheme = $parentTheme;
        $this->childThemeName = $childThemeName;
        $this->childThemeSlug = sanitize_title($childThemeName);
        $this->themesPath = $themesPath ?: get_theme_root();
    }
    
    public function generate() 
    {
        $childThemeDir = $this->themesPath . '/' . $this->childThemeSlug;
        
        if (is_dir($childThemeDir)) {
            throw new Exception("Child theme directory already exists: {$childThemeDir}");
        }
        
        if (!wp_mkdir_p($childThemeDir)) {
            throw new Exception("Failed to create child theme directory: {$childThemeDir}");
        }
        
        $this->createStyleCSS($childThemeDir);
        $this->createFunctionsPHP($childThemeDir);
        
        return $childThemeDir;
    }
    
    private function createStyleCSS($dir) 
    {
        $content = "/*
Theme Name: {$this->childThemeName}
Template: {$this->parentTheme}
Description: Child theme of {$this->parentTheme}
Version: 1.0
*/

/* Add your custom styles here */
";
        
        file_put_contents($dir . '/style.css', $content);
    }
    
    private function createFunctionsPHP($dir) 
    {
        $content = "<?php

// Enqueue parent theme styles
function {$this->childThemeSlug}_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', '{$this->childThemeSlug}_enqueue_styles');

// Add your custom functions here
";
        
        file_put_contents($dir . '/functions.php', $content);
    }
    
    public function setDescription($description) 
    {
        $this->description = $description;
        return $this;
    }
    
    public function setVersion($version) 
    {
        $this->version = $version;
        return $this;
    }
}

// Usage example:
/*
try {
    $generator = new ChildThemeGenerator('twentytwentyfour', 'My Custom Theme');
    $childThemeDir = $generator->generate();
    echo "Child theme created successfully at: {$childThemeDir}";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/
