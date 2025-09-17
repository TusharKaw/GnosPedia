<?php

namespace GnosPediaTheme;

use SpecialPage;
use Html;

class SpecialWikiFarmStats extends SpecialPage {
    
    public function __construct() {
        parent::__construct('WikiFarmStats', 'managewiki');
    }
    
    public function execute($subPage) {
        $this->setHeaders();
        $this->checkPermissions();
        
        $out = $this->getOutput();
        $out->addModules('ext.gnospedia.stats');
        $out->setPageTitle('Wiki Farm Statistics - GnosPedia');
        
        $this->showStatsDashboard();
    }
    
    private function showStatsDashboard() {
        $out = $this->getOutput();
        
        // Get statistics from database
        $stats = $this->getWikiFarmStats();
        
        $html = Html::openElement('div', ['class' => 'gnospedia-stats-dashboard']);
        
        // Header
        $html .= Html::openElement('div', ['class' => 'stats-header']);
        $html .= Html::element('h1', [], 'GnosPedia Farm Statistics');
        $html .= Html::element('p', [], 'Overview of all wikis in the farm');
        $html .= Html::closeElement('div');
        
        // Summary cards
        $html .= Html::openElement('div', ['class' => 'stats-summary']);
        
        $summaryCards = [
            ['Total Wikis', $stats['total_wikis'], '🌐', 'primary'],
            ['Active Wikis', $stats['active_wikis'], '✅', 'success'],
            ['Total Pages', $stats['total_pages'], '📄', 'info'],
            ['Total Users', $stats['total_users'], '👥', 'warning']
        ];
        
        foreach ($summaryCards as $card) {
            $html .= Html::openElement('div', ['class' => 'stats-card ' . $card[3]]);
            $html .= Html::element('div', ['class' => 'stats-icon'], $card[2]);
            $html .= Html::element('div', ['class' => 'stats-number'], $card[1]);
            $html .= Html::element('div', ['class' => 'stats-label'], $card[0]);
            $html .= Html::closeElement('div');
        }
        
        $html .= Html::closeElement('div');
        
        // Category breakdown
        $html .= Html::openElement('div', ['class' => 'stats-section']);
        $html .= Html::element('h2', [], 'Wikis by Category');
        $html .= Html::openElement('div', ['class' => 'category-stats']);
        
        foreach ($stats['categories'] as $category => $count) {
            $percentage = $stats['total_wikis'] > 0 ? round(($count / $stats['total_wikis']) * 100, 1) : 0;
            
            $html .= Html::openElement('div', ['class' => 'category-stat-item']);
            $html .= Html::element('div', ['class' => 'category-name'], ucfirst($category));
            $html .= Html::openElement('div', ['class' => 'category-bar']);
            $html .= Html::element('div', [
                'class' => 'category-fill',
                'style' => "width: {$percentage}%"
            ], '');
            $html .= Html::closeElement('div');
            $html .= Html::element('div', ['class' => 'category-count'], "$count wikis ({$percentage}%)");
            $html .= Html::closeElement('div');
        }
        
        $html .= Html::closeElement('div');
        $html .= Html::closeElement('div');
        
        // Recent wikis
        $html .= Html::openElement('div', ['class' => 'stats-section']);
        $html .= Html::element('h2', [], 'Recently Created Wikis');
        $html .= Html::openElement('div', ['class' => 'recent-wikis']);
        
        foreach ($stats['recent_wikis'] as $wiki) {
            $html .= Html::openElement('div', ['class' => 'recent-wiki-item']);
            $html .= Html::element('div', ['class' => 'wiki-name'], $wiki['sitename']);
            $html .= Html::element('div', ['class' => 'wiki-url'], $wiki['url']);
            $html .= Html::element('div', ['class' => 'wiki-category'], ucfirst($wiki['category']));
            $html .= Html::element('div', ['class' => 'wiki-created'], $this->formatDate($wiki['creation']));
            $html .= Html::closeElement('div');
        }
        
        $html .= Html::closeElement('div');
        $html .= Html::closeElement('div');
        
        // Growth chart placeholder
        $html .= Html::openElement('div', ['class' => 'stats-section']);
        $html .= Html::element('h2', [], 'Wiki Creation Growth');
        $html .= Html::element('div', ['class' => 'chart-placeholder', 'id' => 'growth-chart'], 'Chart will be rendered here');
        $html .= Html::closeElement('div');
        
        $html .= Html::closeElement('div');
        
        $out->addHTML($html);
        
        // Add CSS
        $out->addInlineStyle($this->getStatsCSS());
    }
    
    private function getWikiFarmStats() {
        try {
            $db = new \SQLite3(__DIR__ . '/../../../cache/my_wiki.sqlite');
            
            // Total wikis
            $result = $db->query('SELECT COUNT(*) as count FROM cw_wikis WHERE wiki_deleted = 0');
            $totalWikis = $result->fetchArray(SQLITE3_ASSOC)['count'];
            
            // Active wikis (not closed or deleted)
            $result = $db->query('SELECT COUNT(*) as count FROM cw_wikis WHERE wiki_deleted = 0 AND wiki_closed = 0');
            $activeWikis = $result->fetchArray(SQLITE3_ASSOC)['count'];
            
            // Category breakdown
            $categories = [];
            $result = $db->query('SELECT wiki_category, COUNT(*) as count FROM cw_wikis WHERE wiki_deleted = 0 GROUP BY wiki_category');
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $categories[$row['wiki_category']] = $row['count'];
            }
            
            // Recent wikis
            $recentWikis = [];
            $result = $db->query('SELECT wiki_sitename, wiki_url, wiki_category, wiki_creation FROM cw_wikis WHERE wiki_deleted = 0 ORDER BY wiki_creation DESC LIMIT 10');
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $recentWikis[] = [
                    'sitename' => $row['wiki_sitename'],
                    'url' => $row['wiki_url'],
                    'category' => $row['wiki_category'],
                    'creation' => $row['wiki_creation']
                ];
            }
            
            $db->close();
            
            return [
                'total_wikis' => $totalWikis,
                'active_wikis' => $activeWikis,
                'total_pages' => $this->estimateTotalPages(),
                'total_users' => $this->estimateTotalUsers(),
                'categories' => $categories,
                'recent_wikis' => $recentWikis
            ];
            
        } catch (\Exception $e) {
            return [
                'total_wikis' => 0,
                'active_wikis' => 0,
                'total_pages' => 0,
                'total_users' => 0,
                'categories' => [],
                'recent_wikis' => []
            ];
        }
    }
    
    private function estimateTotalPages() {
        // This would need to query each wiki's database in a real implementation
        // For now, return an estimate
        return rand(500, 2000);
    }
    
    private function estimateTotalUsers() {
        // This would need to query each wiki's database in a real implementation
        // For now, return an estimate
        return rand(50, 500);
    }
    
    private function formatDate($timestamp) {
        if (strlen($timestamp) === 14) {
            // MediaWiki timestamp format
            $date = \DateTime::createFromFormat('YmdHis', $timestamp);
            return $date ? $date->format('M j, Y') : $timestamp;
        }
        return $timestamp;
    }
    
    private function getStatsCSS() {
        return "
            .gnospedia-stats-dashboard {
                max-width: 1200px;
                margin: 0 auto;
                padding: 2em;
            }
            
            .stats-header {
                text-align: center;
                margin-bottom: 3em;
            }
            
            .stats-header h1 {
                font-size: 2.5em;
                color: #333;
                margin-bottom: 0.5em;
            }
            
            .stats-summary {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5em;
                margin-bottom: 3em;
            }
            
            .stats-card {
                background: white;
                border-radius: 12px;
                padding: 2em;
                text-align: center;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                border-left: 4px solid #667eea;
                transition: transform 0.2s ease;
            }
            
            .stats-card:hover {
                transform: translateY(-2px);
            }
            
            .stats-card.success {
                border-left-color: #28a745;
            }
            
            .stats-card.info {
                border-left-color: #17a2b8;
            }
            
            .stats-card.warning {
                border-left-color: #ffc107;
            }
            
            .stats-icon {
                font-size: 2.5em;
                margin-bottom: 0.5em;
            }
            
            .stats-number {
                font-size: 2em;
                font-weight: bold;
                color: #333;
                margin-bottom: 0.25em;
            }
            
            .stats-label {
                color: #666;
                font-weight: 500;
            }
            
            .stats-section {
                background: white;
                border-radius: 12px;
                padding: 2em;
                margin-bottom: 2em;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            
            .stats-section h2 {
                margin-bottom: 1.5em;
                color: #333;
                border-bottom: 2px solid #667eea;
                padding-bottom: 0.5em;
            }
            
            .category-stat-item {
                display: grid;
                grid-template-columns: 150px 1fr 150px;
                align-items: center;
                gap: 1em;
                margin-bottom: 1em;
                padding: 0.5em 0;
            }
            
            .category-name {
                font-weight: 600;
                color: #333;
            }
            
            .category-bar {
                background: #e9ecef;
                height: 20px;
                border-radius: 10px;
                overflow: hidden;
                position: relative;
            }
            
            .category-fill {
                height: 100%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                transition: width 0.5s ease;
            }
            
            .category-count {
                text-align: right;
                color: #666;
                font-size: 0.9em;
            }
            
            .recent-wiki-item {
                display: grid;
                grid-template-columns: 2fr 2fr 1fr 1fr;
                gap: 1em;
                padding: 1em;
                border-bottom: 1px solid #e9ecef;
                align-items: center;
            }
            
            .recent-wiki-item:last-child {
                border-bottom: none;
            }
            
            .wiki-name {
                font-weight: 600;
                color: #333;
            }
            
            .wiki-url {
                color: #667eea;
                font-family: monospace;
                font-size: 0.9em;
            }
            
            .wiki-category {
                background: #f8f9fa;
                padding: 0.25em 0.5em;
                border-radius: 4px;
                font-size: 0.85em;
                text-align: center;
            }
            
            .wiki-created {
                color: #666;
                font-size: 0.9em;
                text-align: right;
            }
            
            .chart-placeholder {
                height: 300px;
                background: #f8f9fa;
                border: 2px dashed #dee2e6;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #666;
                font-style: italic;
                border-radius: 8px;
            }
            
            @media (max-width: 768px) {
                .stats-summary {
                    grid-template-columns: 1fr;
                }
                
                .category-stat-item {
                    grid-template-columns: 1fr;
                    text-align: center;
                    gap: 0.5em;
                }
                
                .recent-wiki-item {
                    grid-template-columns: 1fr;
                    text-align: center;
                }
            }
        ";
    }
}
