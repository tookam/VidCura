'	Copyright (c) 2012 Willy Tekeu <willy.tekeu@vidcura.org>
'	All rights reserved.
'
'	VidCura is distributed under the GNU General Public License, Version 2,
'	June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
'	St, Fifth Floor, Boston, MA 02110, USA
'
'	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
'	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
'	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
'	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
'	ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
'	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
'	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
'	ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
'	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
'	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

Sub Main()     
		' Load channel theme
    InitTheme()
    ' Load root categories
    ShowCategories("0")
    
End Sub

Function GetAuthToken()

    return "<AUTH_TOKEN>" 'don't share it with anyone   
    
End Function

Function GetBaseUrl()

    return "<BASE_URL>"  
    
End Function

Function GetApiBaseUrl()

    return GetBaseUrl() + "/api/roku" 
    
End Function

Sub InitTheme()

    themeUrl = GetApiBaseUrl()+"/"+GetAuthToken()+"/theme-0"
    http = NewHttp(themeUrl)
    response = http.GetToStringWithRetry()
    xml = CreateObject("roXMLElement")
    if not xml.Parse(response) then stop
   	 
   	' Transfer metadata from XML to associative array
    theme = CreateObject("roAssociativeArray")
    GetXMLintoAA(xml, theme)
   
    ' Load and locally save overhang logo/slice images
    http = CreateObject("roUrlTransfer")
    http.SetPort(CreateObject("roMessagePort"))
    http.SetUrl(theme.OverhangSliceSD)
    http.GetToFile("tmp:/"+GetAuthToken()+"overhang_slice_SD.png")
    theme.OverhangSliceSD = "tmp:/"+GetAuthToken()+"overhang_slice_SD.png"
    http.SetUrl(theme.OverhangSliceHD)
    http.GetToFile("tmp:/"+GetAuthToken()+"overhang_slice_HD.png")
    theme.OverhangSliceHD = "tmp:/"+GetAuthToken()+"overhang_slice_HD.png"
    http.SetUrl(theme.OverhangLogoSD)
    http.GetToFile("tmp:/"+GetAuthToken()+"overhang_logo_SD.png")
    theme.OverhangLogoSD = "tmp:/"+GetAuthToken()+"overhang_logo_SD.png"
    http.SetUrl(theme.OverhangLogoHD)
    http.GetToFile("tmp:/"+GetAuthToken()+"overhang_logo_HD.png")
    theme.OverhangLogoHD = "tmp:/"+GetAuthToken()+"overhang_logo_HD.png"
   	
   	' Attaching theme stored in associative array to application manager
    app = CreateObject("roAppManager")
    app.SetTheme(theme)
    
End Sub

Function ShowCategories(parent_id)

    themeUrl = GetApiBaseUrl()+"/"+GetAuthToken()+"/category-"+parent_id
    http = NewHttp(themeUrl)
    response = http.GetToStringWithRetry()
    xml = CreateObject("roXMLElement")
    if not xml.Parse(response) then stop
    
    ' Create array to hold categories
    categories = CreateObject("roArray", 100, true)
   
   	' Let's check if it is a feed of assets
   	' If we are dealing with multimedia assets call proper handler
    if xml.asset.Count() > 0
				ShowAssets(parent_id)
    endif
   
   	' It is a category feed, we transfer the category metadata 
   	' from XML to associative array
    for each category in xml.category
    		o = CreateObject("roAssociativeArray")
        GetXMLintoAA(category, o)   
        categories.Push(o)
    next
  
  	' Create and configure Poster Screen for category display
    screen = CreateObject("roPosterScreen")
    port = CreateObject("roMessagePort")
    screen.SetMessagePort(port)
    screen.SetListStyle("flat-category")
    screen.SetContentList(categories)
    screen.SetFocusedListItem(0)
    screen.SetBreadcrumbText(xml@current, xml@previous)
    screen.Show()

    while true
        msg = wait(0, screen.GetMessagePort())
        if type(msg) = "roPosterScreenEvent" then
            if msg.isListItemSelected() then
            		' A child category has been selected, let's display its content
              	ShowCategories(categories[msg.GetIndex()].Id)
            else if msg.isScreenClosed() then
              	return -1
            end if
        end if
    end while

End Function

Function ShowAssets(parent_id)

    themeUrl = GetApiBaseUrl()+"/"+GetAuthToken()+"/category-"+parent_id
    http = NewHttp(themeUrl)
    response = http.GetToStringWithRetry()
    xml = CreateObject("roXMLElement")
    if not xml.Parse(response) then stop
    
    ' Create array to hold multimedia assets
    assets = CreateObject("roArray", 100, true)
   
   	' Transfer metadata from XML to associative array
    for each asset in xml.asset
        o = CreateObject("roAssociativeArray")
        GetXMLintoAA(asset, o) 
        o.StreamUrls = [o.StreamUrl]
        o.StreamBitrates = [0]
        assets.Push(o)
    next
  
    screen = CreateObject("roPosterScreen")
    port = CreateObject("roMessagePort")
    screen.SetMessagePort(port)
    screen.SetListStyle("flat-episodic")
    screen.SetContentList(assets)
    screen.SetFocusedListItem(0)
    screen.SetBreadcrumbText(xml@current, xml@previous)
    screen.Show()

    while true
        msg = wait(0, screen.GetMessagePort())
        if type(msg) = "roPosterScreenEvent" then
            if msg.isListItemSelected() then
            		' Test whether it is an audio asset (presence of Url) or a cvideo asset
                if assets[msg.GetIndex()].Url <> Invalid then
                		showAudioScreen(assets[msg.GetIndex()])
                else
                		showVideoScreen(assets[msg.GetIndex()])
                end if	
            else if msg.isScreenClosed() then
                return -1
            end if
        end if
    end while

End Function