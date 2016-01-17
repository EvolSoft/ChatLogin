![start2](https://cloud.githubusercontent.com/assets/10303538/6315586/9463fa5c-ba06-11e4-8f30-ce7d8219c27d.png)
# ChatLogin
A ServerAuth extension to do login/register directly on chat

## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP Alpha_1.4 API 1.11.0<br>
**Dependency Plugins:** ServerAuth v2.12 API 1.1.1

## Overview

**ChatLogin** is a ServerAuth extension to do login and register directly in chat

**EvolSoft Website:** http://www.evolsoft.tk

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

**Commands:**

***/chatlogin*** *- ChatLogin commands*

**To-Do:**

<dd><i>- Bug fix (if bugs will be found)</i></dd>

## Documentation

**Configuration (config.yml):**

```yaml
#ChatLogin plugin default configuration
---
#show ChatLogin prefix
show-prefix: true
#Register message
register-message: "&7Type your password in chat to register"
#Require password confirmation for registration
password-confirm-required: true
#Register confirmation message
register-confirm-message: "&bRe-type your password to confirm registration"
#No register permissions message
no-register-permissions: "&cYou don't have permissions to register"
#Login message
login-message: "&7Type your password in chat to login"
#No login permissions message
no-login-permissions: "&cYou don't have permissions to login"
...
```

**Commands:**

***/chatlogin*** *- ChatLogin commands (aliases: [chlogin])*
<br><br>
**Permissions:**
<br>
- <dd><i><b>chatlogin.*</b> - ChatLogin commands permissions.</i></dd>
- <dd><i><b>chatlogin.help</b> - Allows player to show plugin help.</i></dd>
- <dd><i><b>chatlogin.info</b> - Allows player to read info about ChatLogin.</i></dd>
- <dd><i><b>chatlogin.reload</b> - Allows player to reload ChatLogin.</i></dd>
- <dd><i><b>chatlogin.register</b> - Allows player to do register in chat.</i></dd>
- <dd><i><b>chatlogin.login</b> - Allows player to do login in chat.</i></dd>
