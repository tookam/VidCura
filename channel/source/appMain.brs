'	Copyright (c) 2012 Willy Tekeu <willy.tekeu@vidcura.org>
'	All rights reserved.
'
'Permission is hereby granted, free of charge, to any person obtaining
'a copy of this software and associated documentation files (the
'“Software”), to deal in the Software without restriction, including
'without limitation the rights to use, copy, modify, merge, publish,
'distribute, sublicense, and/or sell copies of the Software, and to
'permit persons to whom the Software is furnished to do so, subject to
'the following conditions:
'
'The above copyright notice and this permission notice shall be
'included in all copies or substantial portions of the Software.
'
'THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,
'EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
'MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
'NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
'LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
'OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
'WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

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