## Frequencies api
**Get frequencies**

[GET] `/api/settings/frequencies`

**Save frequency**

[POST] `/api/settings/frequencies`
```json
{
    "name": "Quincenal",
    "days": 15,
    "is_daily": true,
    "is_weekly": false,
    "is_monthly": false,
    "is_every_year": false
}
```
**Update frequency**

[PATCH] `/api/settings/frequencies/{record}`
```json
{
    "name": "Quincenal__",
    "days": 15,
    "is_daily": true,
    "is_weekly": false,
    "is_monthly": false,
    "is_every_year": false
}
```
**Remove frequency**

[DELETE] `/api/settings/frequencies/{record}`
