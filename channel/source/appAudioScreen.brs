Function showAudioScreen(episode As Object)
    port = CreateObject("roMessagePort")
    
    springboard = CreateObject("roSpringboardScreen")
    springboard.SetMessagePort(port)
    springboard.AddButton(1, "Play")
    springboard.SetContent(episode)
    springboard.SetProgressIndicatorEnabled(true)
    springboard.SetStaticRatingEnabled(false)
    springboard.Show()
    
    player = CreateObject("roAudioPlayer")
    player.SetContentList([episode])
    player.SetMessagePort(port)

    while true
        msg = wait(0, port)
        if type(msg) = "roSpringboardScreenEvent" then
            if msg.isScreenClosed() then
                return -1
            elseif msg.isButtonPressed() then
                if msg.GetIndex() = 1 then                    
                    player.Play()
                    springboard.ClearButtons()
                    springboard.AddButton(2, "Pause")
                    springboard.Show()
                elseif msg.GetIndex() = 2 then
                    player.Pause()
                    springboard.ClearButtons()
                    springboard.AddButton(3, "Resume")
                    springboard.Show()
                elseif msg.GetIndex() = 3 then
                    player.Resume()
                    springboard.ClearButtons()
                    springboard.AddButton(2, "Pause")
                    springboard.Show()
                endif
            endif
        endif
    end while

End Function