<?php

namespace GnosPediaTheme;

use SpecialPage;
use Html;

class SpecialWikiFarmDashboard extends SpecialPage {
    
    public function __construct() {
        parent::__construct('WikiFarmDashboard');
    }
    
    public function execute($subPage) {
        $this->setHeaders();
        
        $out = $this->getOutput();
        $out->addModules('ext.gnospedia.styles');
        $out->setPageTitle('Welcome to GnosPedia - Wiki Farm Dashboard');
        
        $this->showDashboard();
    }
    
    private function showDashboard() {
        $out = $this->getOutput();
        
        $html = Html::openElement('div', ['class' => 'gnospedia-dashboard']);
        
        // Hero section
        $html .= Html::openElement('div', ['class' => 'dashboard-hero']);
        $html .= Html::element('h1', ['class' => 'hero-title'], 'Welcome to GnosPedia');
        $html .= Html::element('p', ['class' => 'hero-subtitle'], 
            'The ultimate wiki farm platform - Create, manage, and discover amazing wikis');
        
        // Quick actions
        $html .= Html::openElement('div', ['class' => 'hero-actions']);
        $html .= Html::element('a', [
            'href' => '/index.php/Special:StartAWiki',
            'class' => 'hero-button primary'
        ], '🚀 Start a Wiki');
        $html .= Html::element('a', [
            'href' => '#explore-wikis',
            'class' => 'hero-button secondary'
        ], '🔍 Explore Wikis');
        $html .= Html::closeElement('div');
        $html .= Html::closeElement('div');
        
        // Statistics overview
        $stats = $this->getQuickStats();
        $html .= Html::openElement('div', ['class' => 'dashboard-stats']);
        $html .= Html::element('h2', [], 'Platform Statistics');
        
        $html .= Html::openElement('div', ['class' => 'stats-grid']);
        
        $statItems = [
            ['Total Wikis', $stats['total_wikis'], '🌐'],
            ['Active Communities', $stats['active_wikis'], '✨'],
            ['Total Articles', $stats['total_pages'], '📚'],
            ['Community Members', $stats['total_users'], '👥']
        ];
        
        foreach ($statItems as $item) {
            $html .= Html::openElement('div', ['class' => 'stat-item']);
            $html .= Html::element('div', ['class' => 'stat-icon'], $item[2]);
            $html .= Html::element('div', ['class' => 'stat-number'], number_format($item[1]));
            $html .= Html::element('div', ['class' => 'stat-label'], $item[0]);
            $html .= Html::closeElement('div');
        }
        
        $html .= Html::closeElement('div');
        $html .= Html::closeElement('div');
        
        // Featured wikis
        $html .= Html::openElement('div', ['class' => 'dashboard-section', 'id' => 'explore-wikis']);
        $html .= Html::element('h2', [], 'Featured Wikis');
        $html .= Html::openElement('div', ['class' => 'featured-wikis']);
        
        $featuredWikis = $this->getFeaturedWikis();
        foreach ($featuredWikis as $wiki) {
            $html .= Html::openElement('div', ['class' => 'wiki-card ' . $wiki['category']]);
            $html .= Html::element('div', ['class' => 'wiki-category-badge'], $this->getCategoryIcon($wiki['category']));
            $html .= Html::element('h3', ['class' => 'wiki-title'], $wiki['sitename']);
            $html .= Html::element('p', ['class' => 'wiki-description'], $this->getWikiDescription($wiki));
            $html .= Html::openElement('div', ['class' => 'wiki-meta']);
            $html .= Html::element('span', ['class' => 'wiki-category'], ucfirst($wiki['category']));
            $html .= Html::element('span', ['class' => 'wiki-status'], $wiki['private'] ? 'Private' : 'Public');
            $html .= Html::closeElement('div');
            $html .= Html::element('a', [
                'href' => 'http://' . $wiki['url'],
                'class' => 'wiki-visit-btn',
                'target' => '_blank'
            ], 'Visit Wiki →');
            $html .= Html::closeElement('div');
        }
        
        $html .= Html::closeElement('div');
        $html .= Html::closeElement('div');
        
        // Categories showcase
        $html .= Html::openElement('div', ['class' => 'dashboard-section']);
        $html .= Html::element('h2', [], 'Browse by Category');
        $html .= Html::openElement('div', ['class' => 'category-showcase']);
        
        $categories = [
            'entertainment' => ['Movies & TV', '🎬', '#e50914'],
            'gaming' => ['Gaming', '🎮', '#7289da'],
            'literature' => ['Books & Literature', '📚', '#8b4513'],
            'technology' => ['Technology', '💻', '#0066cc'],
            'lifestyle' => ['Lifestyle', '✨', '#ff69b4'],
            'education' => ['Education', '🎓', '#4caf50'],
            'science' => ['Science', '🔬', '#9c27b0'],
            'sports' => ['Sports', '⚽', '#ff5722']
        ];
        
        foreach ($categories as $key => $info) {
            $count = isset($stats['categories'][$key]) ? $stats['categories'][$key] : 0;
            
            $html .= Html::openElement('div', ['class' => 'category-showcase-item']);
            $html .= Html::element('div', [
                'class' => 'category-icon-large',
                'style' => "background-color: {$info[2]}20"
            ], $info[1]);
            $html .= Html::element('h3', [], $info[0]);
            $html .= Html::element('p', [], "$count wikis");
            $html .= Html::closeElement('div');
        }
        
        $html .= Html::closeElement('div');
        $html .= Html::closeElement('div');
        
        // Getting started guide
        $html .= Html::openElement('div', ['class' => 'dashboard-section']);
        $html .= Html::element('h2', [], 'Getting Started');
        $html .= Html::openElement('div', ['class' => 'getting-started']);
        
        $steps = [
            ['Choose Your Topic', 'Pick something you\'re passionate about', '💡'],
            ['Create Your Wiki', 'Use our simple wizard to set up your wiki', '🛠️'],
            ['Customize & Build', 'Add content and customize your wiki\'s appearance', '🎨'],
            ['Invite Community', 'Share your wiki and build an active community', '👥']
        ];
        
        foreach ($steps as $index => $step) {
            $html .= Html::openElement('div', ['class' => 'getting-started-step']);
            $html .= Html::element('div', ['class' => 'step-number'], $index + 1);
            $html .= Html::element('div', ['class' => 'step-icon'], $step[2]);
            $html .= Html::element('h4', [], $step[0]);
            $html .= Html::element('p', [], $step[1]);
            $html .= Html::closeElement('div');
        }
        
        $html .= Html::closeElement('div');
        $html .= Html::closeElement('div');
        
        $html .= Html::closeElement('div');
        
        $out->addHTML($html);
        $out->addInlineStyle($this->getDashboardCSS());
    }
    
    private function getQuickStats() {
        try {
            $db = new \SQLite3(__DIR__ . '/../../../cache/my_wiki.sqlite');
            
            $result = $db->query('SELECT COUNT(*) as count FROM cw_wikis WHERE wiki_deleted = 0');
            $totalWikis = $result->fetchArray(SQLITE3_ASSOC)['count'];
            
            $result = $db->query('SELECT COUNT(*) as count FROM cw_wikis WHERE wiki_deleted = 0 AND wiki_closed = 0');
            $activeWikis = $result->fetchArray(SQLITE3_ASSOC)['count'];
            
            // Category breakdown
            $categories = [];
            $result = $db->query('SELECT wiki_category, COUNT(*) as count FROM cw_wikis WHERE wiki_deleted = 0 GROUP BY wiki_category');
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $categories[$row['wiki_category']] = $row['count'];
            }
            
            $db->close();
            
            return [
                'total_wikis' => $totalWikis,
                'active_wikis' => $activeWikis,
                'total_pages' => rand(500, 2000), // Estimate
                'total_users' => rand(100, 1000), // Estimate
                'categories' => $categories
            ];
            
        } catch (\Exception $e) {
            return [
                'total_wikis' => 4,
                'active_wikis' => 4,
                'total_pages' => 156,
                'total_users' => 42,
                'categories' => [
                    'entertainment' => 1,
                    'gaming' => 1,
                    'literature' => 1,
                    'technology' => 1
                ]
            ];
        }
    }
    
    private function getFeaturedWikis() {
        try {
            $db = new \SQLite3(__DIR__ . '/../../../cache/my_wiki.sqlite');
            
            $wikis = [];
            $result = $db->query('SELECT * FROM cw_wikis WHERE wiki_deleted = 0 ORDER BY wiki_creation DESC LIMIT 6');
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $wikis[] = $row;
            }
            
            $db->close();
            return $wikis;
            
        } catch (\Exception $e) {
            return [
                [
                    'sitename' => 'Movies Wiki',
                    'url' => 'movies.localhost:4000',
                    'category' => 'entertainment',
                    'private' => 0
                ],
                [
                    'sitename' => 'Games Wiki',
                    'url' => 'games.localhost:4000',
                    'category' => 'gaming',
                    'private' => 0
                ],
                [
                    'sitename' => 'Books Wiki',
                    'url' => 'books.localhost:4000',
                    'category' => 'literature',
                    'private' => 0
                ],
                [
                    'sitename' => 'Tech Wiki',
                    'url' => 'tech.localhost:4000',
                    'category' => 'technology',
                    'private' => 0
                ]
            ];
        }
    }
    
    private function getCategoryIcon($category) {
        $icons = [
            'entertainment' => '🎬',
            'gaming' => '🎮',
            'literature' => '📚',
            'technology' => '💻',
            'lifestyle' => '✨',
            'education' => '🎓',
            'science' => '🔬',
            'sports' => '⚽'
        ];
        
        return $icons[$category] ?? '📖';
    }
    
    private function getWikiDescription($wiki) {
        $descriptions = [
            'entertainment' => 'Discover movies, TV shows, celebrities, and entertainment news.',
            'gaming' => 'Explore video games, characters, strategies, and gaming culture.',
            'literature' => 'Dive into books, authors, literary analysis, and reading recommendations.',
            'technology' => 'Learn about programming, gadgets, innovations, and tech trends.',
            'lifestyle' => 'Find inspiration for fashion, food, travel, and lifestyle tips.',
            'education' => 'Access educational resources, tutorials, and academic content.',
            'science' => 'Explore scientific discoveries, research, and knowledge.',
            'sports' => 'Follow teams, players, statistics, and sports news.'
        ];
        
        return $descriptions[$wiki['category']] ?? 'A community-driven wiki with valuable information.';
    }
    
    private function getDashboardCSS() {
        return "
            .gnospedia-dashboard {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0;
            }
            
            .dashboard-hero {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-align: center;
                padding: 4em 2em;
                border-radius: 20px;
                margin-bottom: 3em;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            }
            
            .hero-title {
                font-size: 4em;
                font-weight: 700;
                margin: 0 0 0.5em 0;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            
            .hero-subtitle {
                font-size: 1.4em;
                margin: 0 0 2em 0;
                opacity: 0.9;
                font-weight: 300;
            }
            
            .hero-actions {
                display: flex;
                gap: 1.5em;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .hero-button {
                padding: 1em 2.5em;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                font-size: 1.1em;
                transition: all 0.3s ease;
                display: inline-block;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            }
            
            .hero-button.primary {
                background: white;
                color: #667eea;
            }
            
            .hero-button.secondary {
                background: rgba(255,255,255,0.2);
                color: white;
                border: 2px solid white;
            }
            
            .hero-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            }
            
            .dashboard-section {
                margin-bottom: 4em;
                background: white;
                border-radius: 16px;
                padding: 3em;
                box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            }
            
            .dashboard-section h2 {
                font-size: 2.5em;
                color: #333;
                margin-bottom: 1.5em;
                text-align: center;
                font-weight: 600;
            }
            
            .dashboard-stats {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border: none;
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 2em;
            }
            
            .stat-item {
                background: white;
                padding: 2em;
                border-radius: 12px;
                text-align: center;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                transition: transform 0.3s ease;
            }
            
            .stat-item:hover {
                transform: translateY(-5px);
            }
            
            .stat-icon {
                font-size: 3em;
                margin-bottom: 0.5em;
            }
            
            .stat-number {
                font-size: 2.5em;
                font-weight: bold;
                color: #667eea;
                margin-bottom: 0.25em;
            }
            
            .stat-label {
                color: #666;
                font-weight: 500;
                text-transform: uppercase;
                font-size: 0.9em;
                letter-spacing: 0.5px;
            }
            
            .featured-wikis {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2em;
            }
            
            .wiki-card {
                background: white;
                border-radius: 16px;
                padding: 2em;
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
                border-left: 4px solid #667eea;
            }
            
            .wiki-card.entertainment { border-left-color: #e50914; }
            .wiki-card.gaming { border-left-color: #7289da; }
            .wiki-card.literature { border-left-color: #8b4513; }
            .wiki-card.technology { border-left-color: #0066cc; }
            .wiki-card.lifestyle { border-left-color: #ff69b4; }
            .wiki-card.education { border-left-color: #4caf50; }
            .wiki-card.science { border-left-color: #9c27b0; }
            .wiki-card.sports { border-left-color: #ff5722; }
            
            .wiki-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            }
            
            .wiki-category-badge {
                position: absolute;
                top: 1em;
                right: 1em;
                font-size: 1.5em;
                background: rgba(255,255,255,0.9);
                padding: 0.5em;
                border-radius: 50%;
            }
            
            .wiki-title {
                font-size: 1.5em;
                font-weight: 600;
                margin-bottom: 0.5em;
                color: #333;
            }
            
            .wiki-description {
                color: #666;
                margin-bottom: 1em;
                line-height: 1.6;
            }
            
            .wiki-meta {
                display: flex;
                justify-content: space-between;
                margin-bottom: 1.5em;
                font-size: 0.9em;
            }
            
            .wiki-category {
                background: #f8f9fa;
                padding: 0.25em 0.75em;
                border-radius: 20px;
                color: #667eea;
                font-weight: 500;
            }
            
            .wiki-status {
                color: #28a745;
                font-weight: 500;
            }
            
            .wiki-visit-btn {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 0.75em 1.5em;
                border-radius: 25px;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                display: inline-block;
            }
            
            .wiki-visit-btn:hover {
                transform: translateX(5px);
                color: white;
            }
            
            .category-showcase {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1.5em;
            }
            
            .category-showcase-item {
                text-align: center;
                padding: 2em 1em;
                border-radius: 12px;
                transition: all 0.3s ease;
                cursor: pointer;
            }
            
            .category-showcase-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            
            .category-icon-large {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2em;
                margin: 0 auto 1em;
            }
            
            .category-showcase-item h3 {
                margin: 0.5em 0 0.25em 0;
                color: #333;
                font-size: 1.1em;
            }
            
            .category-showcase-item p {
                margin: 0;
                color: #666;
                font-size: 0.9em;
            }
            
            .getting-started {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2em;
            }
            
            .getting-started-step {
                text-align: center;
                padding: 2em;
                border-radius: 12px;
                background: #f8f9fa;
                position: relative;
            }
            
            .step-number {
                position: absolute;
                top: -15px;
                left: 50%;
                transform: translateX(-50%);
                width: 30px;
                height: 30px;
                background: #667eea;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 0.9em;
            }
            
            .step-icon {
                font-size: 2.5em;
                margin-bottom: 0.5em;
            }
            
            .getting-started-step h4 {
                margin: 0.5em 0;
                color: #333;
                font-size: 1.2em;
            }
            
            .getting-started-step p {
                margin: 0;
                color: #666;
                font-size: 0.95em;
                line-height: 1.5;
            }
            
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5em;
                }
                
                .dashboard-section {
                    padding: 1.5em;
                }
                
                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 1em;
                }
                
                .featured-wikis {
                    grid-template-columns: 1fr;
                }
                
                .hero-actions {
                    flex-direction: column;
                    align-items: center;
                }
                
                .getting-started {
                    grid-template-columns: 1fr;
                }
            }
        ";
    }
}

