## Habits
[POST] `/api/habits`

```json
{
    "title": "Jerking off",
    "description": "Gets in the way of everything in my life",
    "start": "2021-01-01",
    "goal_date": "2021-01-30",
    "streak_goal": 30,
    "category": 1,
    "counter_goal": 0,
    "is_counter": false,
    "should_keep": false,
    "should_avoid": true
}
```

[POST] `/api/habits/follow-up/{habit}`

```json
{
    "apply_date": "2021-01-02",
    "remove": false,  // If should remove the follow up
    "update": true,   // If the follow up must be updated
    "counter": 1,      // Counter to update
    "accomplished": true,  // If the habit is accompleshed
    "story": "Some story" // Some story for the apply day
}
```

**Get followups per day**

[GET] `/api/habits/daily-follow-up/YYYY-MM-DD`
