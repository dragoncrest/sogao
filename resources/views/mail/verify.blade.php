<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Xác nhận tài khoản</h2>

        <div>
            Cám ơn Quý khách đã đăng ký sử dụng Website của chúng tôi. Xin mời click vào đường link dưới đây để kích hoạt tài khoản Thành viên.<br/>
            {{ URL::to('verify/' . $verification_code) }}.<br/>
        </div>
        <h3>--------------------------------------------------------------------------------------</h3>
        <div>
            Website đang trong giai đoạn hoạt động thử nghiệm, chưa áp dụng quy định thu phí dịch vụ công bố.<br/>
            <div style="margin-top: 5px;">Trong quá trình tiếp tục biên soạn và hoàn thiện nội dung để sớm đưa Website vào hoạt động chính thức, rất mong nhận được sự ủng hộ của Quý khách cả về ý kiến đóng góp và kinh phí hỗ trợ.</div>
            <div style="margin-left: 20px;margin-top: 5px;">1. Ý kiến đóng góp xin gửi về hộp thư GÓP Ý trên trang chủ của Website.</div>
            <div style="margin-left: 20px;">2. Kinh phí hỗ trợ xin gửi về:</div>
            <div style="margin-left: 40px;">- Số tài khoản: 2603205243516 tại Agribank Từ Sơn</div>
            <div style="margin-left: 40px;">- Người nhận: Nguyễn Vinh Cường</div>
            <div style="margin-left: 40px;">- Nội dung: Ủng hộ sotay56 - Tên thành viên</div>
            <div style="margin-top: 5px;">Để đáp lại sự ủng hộ của Quý khách, chúng tôi sẽ gửi đến Quý khách:</div>
            <div style="margin-left: 40px;">- Đối với mỗi ý kiến đóng góp: số KEY tùy thuộc giá trị nội dung góp ý</div>
            <div style="margin-left: 40px;">- Đối với sự hỗ trợ về kinh phí: số KEY tương ứng với số nghìn đồng</div>
            Trân trọng./.
        </div>
    </body>
</html>