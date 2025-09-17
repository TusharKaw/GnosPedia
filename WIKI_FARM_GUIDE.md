# 🌟 GnosPedia Wiki Farm - Complete User Guide

## 🎉 **Congratulations! Your Wiki Farm is Now Fully Operational!**

You now have a complete Fandom-like wiki farm with 4 powerful extensions working together:

### 🔧 **Installed Extensions:**

1. **MediaWikiFarm** - Core farm framework for subdomain routing
2. **CreateWiki** - Automated wiki creation system
3. **ManageWiki** - Wiki management and configuration
4. **GnosPediaTheme** - Custom theme and special pages

---

## 🚀 **How to Use Your Wiki Farm**

### **1. Starting the Server**
```bash
cd /Users/mohitkumar/Downloads/PProjects/wikipedia_v2/GnosPedia
./start-gnospedia.sh
```

### **2. Accessing Your Wiki Farm**

#### **Main Wiki (Farm Hub)**
- **URL**: `http://localhost:4000`
- **Purpose**: Central hub for managing all wikis
- **Features**: 
  - Create new wikis
  - Manage existing wikis
  - View farm statistics
  - User account management

#### **Subdomain Wikis**
- **Movies Wiki**: `http://movies.localhost:4000`
- **Games Wiki**: `http://games.localhost:4000`
- **Books Wiki**: `http://books.localhost:4000`
- **Tech Wiki**: `http://tech.localhost:4000`

---

## 🛠️ **Using the Extensions**

### **📝 CreateWiki Extension**

#### **What it does:**
- Automatically creates new wikis
- Sets up databases and configurations
- Handles subdomain routing
- Manages wiki permissions

#### **How to use:**
1. **Access**: Go to `http://localhost:4000/index.php/Special:CreateWiki`
2. **Create Account**: First, create a user account
3. **Fill Form**: 
   - Wiki Name: `Movies Wiki`
   - Subdomain: `movies`
   - Category: `Entertainment`
   - Description: `A wiki about movies`
4. **Submit**: Click "Create Wiki"
5. **Result**: New wiki available at `http://movies.localhost:4000`

#### **Features:**
- ✅ Automatic database creation
- ✅ Subdomain routing setup
- ✅ Upload directory creation
- ✅ Permission management
- ✅ Wiki categorization

### **⚙️ ManageWiki Extension**

#### **What it does:**
- Manages wiki settings without editing PHP files
- Controls extensions per wiki
- Manages namespaces
- Handles permissions
- Configures wiki-specific settings

#### **How to use:**
1. **Access**: Go to `http://localhost:4000/index.php/Special:ManageWiki`
2. **Select Wiki**: Choose which wiki to manage
3. **Configure**:
   - **Core Settings**: Site name, logo, language
   - **Extensions**: Enable/disable extensions per wiki
   - **Namespaces**: Add custom namespaces
   - **Permissions**: Set user group permissions
   - **Settings**: Wiki-specific configurations

#### **Available Management Options:**
- 🎨 **Appearance**: Logo, theme, colors
- 🔌 **Extensions**: Enable/disable extensions per wiki
- 📁 **Namespaces**: Create custom content types
- 👥 **Permissions**: Control who can do what
- ⚙️ **Settings**: Wiki-specific configurations

### **🏗️ MediaWikiFarm Extension**

#### **What it does:**
- Handles subdomain routing automatically
- Manages farm configuration
- Routes requests to correct wikis
- Manages farm-wide settings

#### **Configuration Files:**
- `config/farms.yml` - Farm routing rules
- `config/FarmSettings.php` - Subdomain wiki settings
- `config/MainSettings.php` - Main wiki settings

#### **Automatic Features:**
- ✅ Subdomain detection (`movies.localhost:4000` → `movies_wiki` database)
- ✅ Database switching
- ✅ Upload directory routing
- ✅ Site name customization
- ✅ Logo management

### **🎨 GnosPediaTheme Extension**

#### **What it does:**
- Provides custom styling
- Adds special pages
- Enhances user experience
- Provides farm-specific features

#### **Special Pages:**
- **Start a Wiki**: `http://localhost:4000/index.php/Special:StartAWiki`
- **Wiki Farm Stats**: `http://localhost:4000/index.php/Special:WikiFarmStats`
- **Wiki Farm Dashboard**: `http://localhost:4000/index.php/Special:WikiFarmDashboard`

---

## 📋 **Step-by-Step Tutorial: Creating Your First Wiki**

### **Step 1: Create a User Account**
1. Go to `http://localhost:4000`
2. Click "Create account" in the top right
3. Fill in your details and create account
4. Log in with your new account

### **Step 2: Create a New Wiki**
1. Go to `http://localhost:4000/index.php/Special:CreateWiki`
2. Fill in the form:
   - **Wiki Name**: `Movies Wiki`
   - **Subdomain**: `movies`
   - **Category**: `Entertainment`
   - **Description**: `A comprehensive wiki about movies`
3. Click "Create Wiki"
4. Wait for the creation process to complete

### **Step 3: Access Your New Wiki**
1. Go to `http://movies.localhost:4000`
2. You'll see your new wiki with:
   - Custom site name: "Movies Wiki"
   - Dedicated database: `movies_wiki`
   - Upload directory: `/images/movies/`
   - All MediaWiki features enabled

### **Step 4: Customize Your Wiki**
1. Go to `http://localhost:4000/index.php/Special:ManageWiki`
2. Select "Movies Wiki" from the dropdown
3. Configure:
   - **Logo**: Upload a custom logo
   - **Theme**: Choose colors and styling
   - **Extensions**: Enable additional features
   - **Permissions**: Set who can edit

---

## 🌐 **Subdomain Setup (Optional)**

To use subdomains on your local machine, add these entries to `/etc/hosts`:

```bash
sudo nano /etc/hosts
```

Add these lines:
```
127.0.0.1 movies.localhost
127.0.0.1 games.localhost
127.0.0.1 books.localhost
127.0.0.1 tech.localhost
```

---

## 🔧 **Advanced Configuration**

### **Database Structure**
```
cache/
├── my_wiki.sqlite          # Main wiki database
├── wikis/                  # Subdomain wiki databases
│   ├── movies_wiki.sqlite
│   ├── games_wiki.sqlite
│   └── books_wiki.sqlite
└── farm/                   # Farm cache and logs
```

### **Upload Directories**
```
images/
├── main/                   # Main wiki uploads
├── movies/                 # Movies wiki uploads
├── games/                  # Games wiki uploads
└── books/                  # Books wiki uploads
```

### **Configuration Files**
```
config/
├── farms.yml              # Farm routing rules
├── FarmSettings.php       # Subdomain wiki settings
├── MainSettings.php       # Main wiki settings
└── LocalSettings.php      # Base MediaWiki config
```

---

## 🎯 **Use Cases & Examples**

### **1. Entertainment Network**
- **Main**: `localhost:4000` (Entertainment Hub)
- **Movies**: `movies.localhost:4000`
- **Games**: `games.localhost:4000`
- **Music**: `music.localhost:4000`
- **TV Shows**: `tv.localhost:4000`

### **2. Educational Platform**
- **Main**: `localhost:4000` (Learning Hub)
- **Math**: `math.localhost:4000`
- **Science**: `science.localhost:4000`
- **History**: `history.localhost:4000`
- **Literature**: `literature.localhost:4000`

### **3. Business Documentation**
- **Main**: `localhost:4000` (Company Hub)
- **HR**: `hr.localhost:4000`
- **IT**: `it.localhost:4000`
- **Marketing**: `marketing.localhost:4000`
- **Finance**: `finance.localhost:4000`

---

## 🚨 **Troubleshooting**

### **Common Issues:**

#### **1. "Wiki does not exist" Error**
- **Solution**: Make sure the wiki was created successfully
- **Check**: Go to `Special:CreateWiki` and verify the wiki exists

#### **2. Subdomain Not Working**
- **Solution**: Check `/etc/hosts` file has the subdomain entry
- **Test**: `ping movies.localhost` should return `127.0.0.1`

#### **3. Database Errors**
- **Solution**: Check database files exist in `cache/wikis/`
- **Fix**: Recreate the wiki if database is corrupted

#### **4. Permission Errors**
- **Solution**: Check file permissions on `cache/` and `images/` directories
- **Fix**: `chmod -R 755 cache/ images/`

---

## 🎉 **You're All Set!**

Your GnosPedia Wiki Farm is now fully operational with:

✅ **MediaWikiFarm** - Subdomain routing and farm management  
✅ **CreateWiki** - Automated wiki creation  
✅ **ManageWiki** - Wiki configuration management  
✅ **GnosPediaTheme** - Custom styling and special pages  

**Start creating wikis and building your community today!** 🌟

---

## 📞 **Need Help?**

- **Server Issues**: Check the startup script `./start-gnospedia.sh`
- **Configuration**: Edit files in `config/` directory
- **Extensions**: Check `extensions/` directory for extension files
- **Logs**: Check `cache/farm.log` for farm-related issues

**Happy Wiki Farming!** 🚀
