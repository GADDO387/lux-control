import serial
import time

# Establish serial communication with the Arduino
arduino = serial.Serial('/dev/ttyUSB0', 9600)  # Adjust '/dev/ttyUSB0' as needed
time.sleep(2)  # Wait for the connection to be established

def send_setpoint(setpoint):
    arduino.write(f"{setpoint}\n".encode())

def read_lux():
    while arduino.in_waiting == 0:
        pass
    return arduino.readline().decode().strip()

if __name__ == "__main__":
    setpoint = float(input("Enter the desired lux setpoint: "))
    send_setpoint(setpoint)
    
    while True:
        lux = read_lux()
        print(f"Current lux: {lux}")
        time.sleep(1)  # Adjust as needed
