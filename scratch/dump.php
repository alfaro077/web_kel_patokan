<?php
echo "MENUS:\n" . \App\Models\NavigationMenu::get()->toJson() . "\n\n";
echo "PAGES:\n" . \App\Models\Page::get()->toJson() . "\n";
