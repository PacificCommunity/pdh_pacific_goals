## INTRODUCTION

The PDH Pacific Goals module is a Drupal module that creates 2 blocks

1. SDGs: showing a grid of the SDGs.
2. BP2050: showing a grid of Blue Pacific 2050 themes.

Each tile can be active or inactive (grayed out) and links to the Pacific Data Hub dashboards when active.

## REQUIREMENTS

- Content Translation
- Metatag

## INSTALLATION

Step 1: Add repository in composer.json

```
{
    "repositories": {
        "pdh_pacific_goals": {
            "type": "vcs",
            "url": "git@github.com:pacificCommunity/pdh_pacific_goals.git"
        }
    }
}
```

Step 2: Install via composer

```
composer require pacific_community/pdh_pacific_goals:^1.0
```

Step 3: Enable via drush (or GUI)

```
drush en pdh_pacific_goals
```

Step 4 (optional): Create a new paragraph type
