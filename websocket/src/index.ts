import { Server } from "socket.io";
import { createServer } from "node:http";

const httpServer = createServer();
const io = new Server(httpServer, {
    cors: { origin: '*' }
});

io.on('connection', (socket) => {
    console.log("Client connected: ", socket.id);

    socket.on('join', (userId: string) => {
        socket.join(`user:${userId}`);
        console.log(`User ${userId} joined`);
    });

    socket.on('message', (data: {recipientId: string; senderId: string; content: string}) => {
        console.log("received: ", data);
        io.to(`user:${data.recipientId}`).emit('message', {
            senderId: data.senderId,
            content: data.content
        });
    });

    socket.on('notification', (data: { recipientId: string }) => {
        io.to(`user:${data.recipientId}`).emit('notification');
    });

    socket.on('disconnect', () => {
        console.log("Client disconnected.");
    });
});

const PORT = process.env.SOCKET_PORT || 3000;

httpServer.listen(PORT, () => {
    console.log('Socket.io running on port ' + PORT);
});
