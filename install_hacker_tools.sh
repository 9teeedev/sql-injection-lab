#!/bin/bash

# ==========================================
#  AUTO INSTALLER: Security Tools (V.3 Final)
#  Fixes: Python path issues & Updates tools
# ==========================================

# Colors for output
GREEN='\033[0;32m'
CYAN='\033[0;36m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${CYAN}=========================================${NC}"
echo -e "${CYAN}   🚀 INSTALLING SECURITY LAB TOOLS      ${NC}"
echo -e "${CYAN}=========================================${NC}"

# 1. Root Check
if [ "$EUID" -ne 0 ]; then
  echo -e "${RED}[!] Error: Please run as root (sudo ./install.sh)${NC}"
  exit 1
fi

# 2. Update System & Install Dependencies
echo -e "${GREEN}[+] Updating system repositories...${NC}"
apt-get update -qq > /dev/null

echo -e "${GREEN}[+] Installing dependencies (Git, Python3)...${NC}"
apt-get install -y git python3 python3-pip -qq > /dev/null

# ----------------------------------------------------
# 3. SQLMap Installation (Git Source + Wrapper Fix)
# ----------------------------------------------------
echo -e "${GREEN}[+] Installing SQLMap (Latest from Git)...${NC}"

# Remove old versions to prevent conflicts
if dpkg -s sqlmap &> /dev/null; then
    apt-get remove -y sqlmap -qq > /dev/null
fi
rm -rf /opt/sqlmap
rm -f /usr/bin/sqlmap

# Clone latest source
git clone --depth 1 https://github.com/sqlmapproject/sqlmap.git /opt/sqlmap -q

# 🔥 THE FIX: Create a wrapper script to force Python 3
# This solves the "python: No such file or directory" error perfectly.
cat <<EOF > /usr/bin/sqlmap
#!/bin/bash
exec python3 /opt/sqlmap/sqlmap.py "\$@"
EOF

# Make it executable
chmod +x /usr/bin/sqlmap

# ----------------------------------------------------
# 4. Nikto Installation
# ----------------------------------------------------
echo -e "${GREEN}[+] Installing Nikto...${NC}"
apt-get install -y nikto -qq > /dev/null

# ----------------------------------------------------
# 5. Verification
# ----------------------------------------------------
echo -e "${CYAN}=========================================${NC}"
echo -e "${GREEN}[+] Verification Results:${NC}"

# Check SQLMap
if [ -f "/usr/bin/sqlmap" ]; then
    SQL_VER=$(sqlmap --version 2>&1 | head -n 1)
    echo -e "✅ SQLMap Status: Ready"
    echo -e "   Version: ${CYAN}$SQL_VER${NC}"
    echo -e "   Path: /opt/sqlmap (Linked to /usr/bin/sqlmap)"
else
    echo -e "${RED}❌ SQLMap installation failed!${NC}"
fi

echo -e "-----------------------------------------"

# Check Nikto
if command -v nikto &> /dev/null; then
    NIKTO_VER=$(nikto -Version 2>&1 | head -n 1)
    echo -e "✅ Nikto Status: Ready"
    echo -e "   ${CYAN}$NIKTO_VER${NC}"
else
    echo -e "${RED}❌ Nikto installation failed!${NC}"
fi

echo -e "${CYAN}=========================================${NC}"
echo -e "${GREEN}🎉 All done! You are ready to hack.${NC}"