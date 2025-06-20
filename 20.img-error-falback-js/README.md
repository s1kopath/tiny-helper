# 20. img error falback js

### usage
```code
    <img src="/img.png" alt="img" 
    onerror="this.onerror=null;this.src='/error.png';" />
```