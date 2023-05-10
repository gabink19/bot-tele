# export GO111MODULE=on
pwd
export GOPATH=/home/system_user/go
export GOCACHE=/home/system_user/go/cache
cd /var/www/html/radmed.co.id/bot-tele/quote-maker/
echo $1
echo $2
echo $3
# go mod tidy
go build
go run /var/www/html/radmed.co.id/bot-tele/quote-maker/main.go $1 $2 $3 $4
