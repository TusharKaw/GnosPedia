<?php

namespace GnosPediaTheme;

use MediaWiki\Hook\BeforePageDisplayHook;
use MediaWiki\Hook\SkinTemplateNavigation__UniversalHook;
use OutputPage;
use Skin;
use SkinTemplate;

class Hooks implements BeforePageDisplayHook, SkinTemplateNavigation__UniversalHook {
    
    public function onBeforePageDisplay($out, $skin): void {
        $out->addModules('ext.gnospedia.styles');
        
        // Add wiki-specific styling based on category
        $this->addWikiSpecificStyling($out);
    }
    
    public function onSkinTemplateNavigation__Universal($sktemplate, &$links): void {
        // Add "Start a Wiki" link to main navigation
        if ($this->isMainWiki()) {
            $links['namespaces']['start-wiki'] = [
                'text' => 'Start a Wiki',
                'href' => '/index.php/Special:StartAWiki',
                'class' => 'gnospedia-start-wiki-link'
            ];
        }
        
        // Add "Manage Wiki" link for wiki admins
        if ($this->canManageWiki($sktemplate->getUser())) {
            $links['namespaces']['manage-wiki'] = [
                'text' => 'Manage Wiki',
                'href' => '/index.php/Special:ManageWiki',
                'class' => 'gnospedia-manage-wiki-link'
            ];
        }
    }
    
    private function addWikiSpecificStyling($out) {
        // Get current wiki info
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:4000';
        $isSubdomain = preg_match('/^([a-z0-9-]+)\.localhost:4000$/', $host, $matches);
        
        if ($isSubdomain) {
            $wikiName = $matches[1];
            
            // Add category-specific CSS class to body
            $category = $this->getWikiCategory($wikiName);
            if ($category) {
                $out->addBodyClasses("gnospedia-category-$category");
            }
            
            // Add custom CSS variables for theming
            $customCSS = $this->generateCategoryThemeCSS($category);
            if ($customCSS) {
                $out->addInlineStyle($customCSS);
            }
        }
    }
    
    private function getWikiCategory($wikiName) {
        try {
            $db = new \SQLite3(__DIR__ . '/../../../cache/my_wiki.sqlite');
            $stmt = $db->prepare('SELECT wiki_category FROM cw_wikis WHERE wiki_dbname = ?');
            $stmt->bindValue(1, $wikiName . '_wiki');
            $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);
            $db->close();
            
            return $row ? $row['wiki_category'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function generateCategoryThemeCSS($category) {
        $themes = [
            'entertainment' => [
                'primary' => '#e50914',
                'secondary' => '#221f1f',
                'accent' => '#f5c518'
            ],
            'gaming' => [
                'primary' => '#7289da',
                'secondary' => '#2c2f33',
                'accent' => '#99aab5'
            ],
            'literature' => [
                'primary' => '#8b4513',
                'secondary' => '#f4f4f2',
                'accent' => '#daa520'
            ],
            'technology' => [
                'primary' => '#0066cc',
                'secondary' => '#f8f9fa',
                'accent' => '#28a745'
            ],
            'lifestyle' => [
                'primary' => '#ff69b4',
                'secondary' => '#fff0f5',
                'accent' => '#ffd700'
            ],
            'education' => [
                'primary' => '#4caf50',
                'secondary' => '#f1f8e9',
                'accent' => '#2196f3'
            ],
            'science' => [
                'primary' => '#9c27b0',
                'secondary' => '#f3e5f5',
                'accent' => '#ff9800'
            ],
            'sports' => [
                'primary' => '#ff5722',
                'secondary' => '#fff3e0',
                'accent' => '#4caf50'
            ]
        ];
        
        if (!isset($themes[$category])) {
            return '';
        }
        
        $theme = $themes[$category];
        
        return "
            :root {
                --gnospedia-primary: {$theme['primary']};
                --gnospedia-secondary: {$theme['secondary']};
                --gnospedia-accent: {$theme['accent']};
            }
            
            .mw-body h1, .mw-body h2 {
                color: var(--gnospedia-primary);
            }
            
            .mw-body a {
                color: var(--gnospedia-primary);
            }
            
            .mw-body a:hover {
                color: var(--gnospedia-accent);
            }
            
            #mw-page-base {
                background: linear-gradient(135deg, var(--gnospedia-secondary) 0%, #ffffff 100%);
            }
        ";
    }
    
    private function isMainWiki() {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:4000';
        return $host === 'localhost:4000';
    }
    
    private function canManageWiki($user) {
        return $user && ($user->isAllowed('managewiki') || $user->isAllowed('sysop'));
    }
}

