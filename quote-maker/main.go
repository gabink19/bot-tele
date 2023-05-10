package main

import (
	"bytes"
	"encoding/base64"
	"encoding/json"
	"fmt"
	"image"
	"image/jpeg"
	"image/png"
	"io/ioutil"
	"log"
	"net/http"
	"os"
	"strconv"

	_ "embed"

	tgbotapi "github.com/go-telegram-bot-api/telegram-bot-api"
	"github.com/golang/freetype"
	"github.com/golang/freetype/truetype"
	"github.com/joho/godotenv"
	"golang.org/x/image/draw"
	"golang.org/x/image/math/fixed"
)

//go:embed HiddenCocktails.ttf
var fontAlibaba []byte

//go:embed default-avatar.png
var defaultAvatar []byte

//go:embed gradient-mask.png
var maskFile []byte

func main() {
	// banner
	if len(os.Args) < 4 {
		fmt.Println("Please provide a name as an argument")
		os.Exit(1)
	}

	encodedName := os.Args[1]
	encodedString := os.Args[2]
	userID, _ := strconv.Atoi(os.Args[3])

	fmt.Printf("=================\n")
	fmt.Printf("MAKE IT A QUOTE\n")
	fmt.Printf("Version: %s\n", "1")
	fmt.Printf("Environment: %s\n", "Test")

	font, err := freetype.ParseFont(fontAlibaba)
	if err != nil {
		log.Fatalf("processors register failed", err.Error())
	}
	// load avatar
	defaultAvatar, err := png.Decode(bytes.NewBuffer(defaultAvatar))
	if err != nil {
		log.Fatalf("processors register failed", err.Error())
	}
	// load mask
	mask, err := png.Decode(bytes.NewBuffer(maskFile))
	if err != nil {
		log.Fatalf("processors register failed", err.Error())
	}

	// generate text image
	options := &TextToImageOptions{
		Font:     font,
		FontSize: 15,
		DPI:      170,
		Padding:  40,
		MaxWidth: 700,
	}
	decodedBytes, err := base64.StdEncoding.DecodeString(encodedString)
	if err != nil {
		fmt.Println("Error decoding base64:", err.Error())
		return
	}
	decodedName, err := base64.StdEncoding.DecodeString(encodedName)
	if err != nil {
		fmt.Println("Error decoding base64:", err.Error())
		return
	}
	textImg := TextToImage(string(decodedBytes), options)
	options.FontSize = 12
	usernameImg := TextToImage("@"+string(decodedName), options)

	// combine text and username TODO: can be optimized to reduce a image
	width, height := textImg.Rect.Dx(), textImg.Rect.Dy()+usernameImg.Rect.Dy()
	textAndUsername := image.NewRGBA(image.Rect(0, 0, width, height))
	draw.Draw(textAndUsername, textImg.Bounds(), textImg, image.Point{}, draw.Over)
	draw.Draw(textAndUsername, image.Rect(0, textImg.Rect.Dy(), width, height), usernameImg, image.Point{}, draw.Over)

	avatar := getAvatar(userID)
	// sacle avatar
	avatarImg := defaultAvatar
	if len(avatar) != 0 {
		avatarImg, err = jpeg.Decode(bytes.NewBuffer(avatar)) // TODO: can load in initial func
		if err != nil {
			log.Fatalf("decode avatar failed, err: %s, id: %d, chat id: %d", err.Error(), "msg.MessageID", "msg.Chat.ID")
		}
	}
	avatarAfterScale := image.NewRGBA(image.Rect(0, 0, height, height))
	draw.ApproxBiLinear.Scale(avatarAfterScale, avatarAfterScale.Rect, avatarImg, avatarImg.Bounds(), draw.Over, nil)
	// combine text, username and avatar
	result := image.NewRGBA(image.Rect(0, 0, avatarAfterScale.Rect.Dx()+textAndUsername.Rect.Dx(), height))
	draw.Draw(result, avatarAfterScale.Bounds(), avatarAfterScale, image.Point{}, draw.Over)
	draw.Draw(result, image.Rect(height, 0, width+height, height), textAndUsername, image.Point{}, draw.Over)
	// add mask
	mask, err = png.Decode(bytes.NewBuffer(maskFile)) // TODO: can load in initial func
	if err != nil {
		log.Fatalf("decode mask failed, err: %s, id: %d, chat id: %d", err.Error(), "msg.MessageID", "msg.Chat.ID")
	}
	maskAfterScale := image.NewRGBA(image.Rect(0, 0, height, height))
	draw.ApproxBiLinear.Scale(maskAfterScale, maskAfterScale.Rect, mask, mask.Bounds(), draw.Over, nil)
	draw.Draw(result, maskAfterScale.Bounds(), maskAfterScale, image.Point{}, draw.Over)
	resultBuff := bytes.NewBuffer([]byte{})
	err = png.Encode(resultBuff, result)
	if err != nil {
		log.Fatalf("encode image failed, err: %s, id: %d, chat id: %d", err.Error(), "msg.MessageID", "msg.Chat.ID")
	}
	err = ioutil.WriteFile("../public/quote/"+os.Args[4]+".png", resultBuff.Bytes(), 0755)
	if err != nil {
		println("WriteFile: ", err)
		// handle error
	}
	fmt.Printf("=======DONE=======\n")
}

// TextToImageOptions are options struct for TextToImage function.
// If max width is 0, will use 400 instead.
type TextToImageOptions struct {
	Font     *truetype.Font
	FontSize float64
	DPI      float64

	Padding  int
	MaxWidth int
}

// TextToImage converts text to a png image.
func TextToImage(msg string, options *TextToImageOptions) *image.RGBA {
	ctx := freetype.NewContext()
	ctx.SetFontSize(options.FontSize)
	ctx.SetFont(options.Font)
	ctx.SetDPI(options.DPI)
	ctx.SetSrc(image.White)

	// measure text size and split to lines.
	face := truetype.NewFace(options.Font, &truetype.Options{
		Size: options.FontSize,
		DPI:  options.DPI,
	})

	line := ""
	splittedText := []string{}
	x := options.Padding
	height := ctx.PointToFixed(options.FontSize).Ceil() + options.Padding*3
	for _, c := range msg {
		width, _ := face.GlyphAdvance(c)

		// new line
		if x+width.Round() > options.MaxWidth-options.Padding*2 {
			splittedText = append(splittedText, line)
			line = string(c)
			height += ctx.PointToFixed(options.FontSize).Ceil()
			x = width.Ceil() + options.Padding
			continue
		}
		// add rune to line
		line += string(c)
		x += width.Ceil()
	}
	// add last line
	splittedText = append(splittedText, line)
	height += ctx.PointToFixed(options.FontSize).Ceil()

	println("text size: ", options.MaxWidth, height)
	// create a empty image
	dist := image.NewRGBA(image.Rect(0, 0, options.MaxWidth, height))
	draw.Draw(dist, dist.Bounds(), image.Black, image.Point{}, draw.Over)
	// draw line
	ctx.SetDst(dist)
	ctx.SetClip(dist.Bounds())
	ctx.SetSrc(image.White)

	for i, line := range splittedText {
		ctx.DrawString(line, fixed.Point26_6{
			X: ctx.PointToFixed(float64(options.Padding)),
			Y: ctx.PointToFixed(float64(i+1)*options.FontSize + float64(options.Padding)),
		})
		println("drawing: ", line, i, i*int(options.FontSize))
	}

	return dist
}

func getAvatar(userID int) []byte {
	godotenv.Load(".env")
	var fileID string
	bot, err := tgbotapi.NewBotAPI(os.Getenv("TOKEN"))
	if err != nil {
		return []byte{}
	}

	// Set the maximum number of profile photos to retrieve
	limit := 1

	// Create the request object
	request := tgbotapi.NewUserProfilePhotos(userID)
	request.Limit = limit

	// Send the request and retrieve the response
	photos, err := bot.GetUserProfilePhotos(request)
	if err != nil {
		println("photos: ", err)
		log.Fatal(err)
	}

	// Iterate over the retrieved photos and do something with them
	for _, photo := range photos.Photos {
		for _, file := range photo {
			fileID = file.FileID
		}
	}

	println("fileID: ", fileID)
	botToken := os.Getenv("TOKEN")
	filePath := fmt.Sprintf("https://api.telegram.org/bot%s/getFile?file_id=%s", botToken, fileID)

	// membuat HTTP request ke API Telegram
	resp, err := http.Get(filePath)
	if err != nil {
		println("resp: ", resp)
		return []byte{}
	}
	defer resp.Body.Close()

	// parsing response untuk mendapatkan path file
	var fileResp struct {
		Result struct {
			FilePath string `json:"file_path"`
		} `json:"result"`
	}
	if err := json.NewDecoder(resp.Body).Decode(&fileResp); err != nil {
		println("NewDecoder: ", err)
		return []byte{}
	}

	// membuat HTTP request untuk download file
	fileURL := fmt.Sprintf("https://api.telegram.org/file/bot%s/%s", botToken, fileResp.Result.FilePath)
	resp, err = http.Get(fileURL)
	if err != nil {
		println("err: ", err)
		return []byte{}
	}
	defer resp.Body.Close()

	data, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		println("ReadAll: ", err)
		return []byte{}
	}

	return data
}
