import javax.swing.*;
import javax.swing.plaf.ColorUIResource;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.ArrayList;

public class UserLoginGui extends JFrame implements ActionListener {

    private JPanel panel1, panel2, usernamePanel, passwordPanel;
    private JTextField usernameField;
    private JPasswordField passwordField;
    private JButton loginButton, clearButton, exitButton;
    private JLabel usernameLabel, passwordLabel;
    private JMenuItem loginMenuItem, clearMenuItem, exitMenuItem;
    private ArrayList<User> UserList;

    public UserLoginGui() {
        super("CPUT USER");

        // Set custom icon
        ImageIcon icon = new ImageIcon("download.png");
        setIconImage(icon.getImage());

        // Create a gradient panel for the top
        JPanel topPanel = new JPanel() {
            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                Graphics2D g2d = (Graphics2D) g;
                int w = getWidth();
                int h = getHeight();
                Color color1 = new Color(0, 0, 255); // Blue
                Color color2 = new Color(173, 216, 230); // Light Blue
                GradientPaint gp = new GradientPaint(0, 0, color1, 0, h, color2);
                g2d.setPaint(gp);
                g2d.fillRect(0, 0, w, h);
            }
        };
        topPanel.setLayout(new BorderLayout());

        panel1 = new JPanel(new GridLayout(2, 1));

        usernamePanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        usernameLabel = new JLabel("Username:");
        usernameField = new JTextField(20);
        usernamePanel.add(usernameLabel);
        usernamePanel.add(usernameField);
        panel1.add(usernamePanel);

        passwordPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        passwordLabel = new JLabel("Password:");
        passwordField = new JPasswordField(20);
        passwordPanel.add(passwordLabel);
        passwordPanel.add(passwordField);
        panel1.add(passwordPanel);

        topPanel.add(panel1, BorderLayout.CENTER);

        // Create a panel for the bottom
        JPanel bottomPanel = new JPanel() {
            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                Graphics2D g2d = (Graphics2D) g;
                int w = getWidth();
                int h = getHeight();
                Color color1 = new Color(173, 216, 230); // Light Blue
                Color color2 = new Color(173, 216, 230); // White
                GradientPaint gp = new GradientPaint(0, 0, color1, 0, h, color2);
                g2d.setPaint(gp);
                g2d.fillRect(0, 0, w, h);
            }
        };
        bottomPanel.setPreferredSize(new Dimension(0, 50)); // Set preferred height for the bottom panel

        panel2 = new JPanel();
        panel2.setLayout(new GridLayout(1, 3));

        loginButton = new JButton("Login");
        clearButton = new JButton("Clear");
        exitButton = new JButton("Exit");

        loginButton.setForeground(Color.BLACK);
        loginButton.setBackground(Color.GREEN);

        clearButton.setForeground(Color.BLACK);
        clearButton.setBackground(Color.BLUE);

        exitButton.setForeground(Color.BLACK);
        exitButton.setBackground(Color.RED);

        panel2.add(loginButton);
        panel2.add(clearButton);
        panel2.add(exitButton);

        bottomPanel.add(panel2);
         
        add(topPanel, BorderLayout.CENTER);
        add(bottomPanel, BorderLayout.SOUTH);

        JMenuBar menuBar = new JMenuBar();
        JMenu menu = new JMenu("Menu");

        loginMenuItem = new JMenuItem("Login");
        clearMenuItem = new JMenuItem("Clear");
        exitMenuItem = new JMenuItem("Exit");

        // Set icon for menu items
        loginMenuItem.setIcon(icon);
        clearMenuItem.setIcon(icon);
        exitMenuItem.setIcon(icon);

        loginMenuItem.addActionListener(this);
        clearMenuItem.addActionListener(this);
        exitMenuItem.addActionListener(this);

        menu.add(loginMenuItem);
        menu.add(clearMenuItem);
        menu.add(exitMenuItem);
        menuBar.add(menu);

        setJMenuBar(menuBar);

        // Change menu bar color
        UIManager.put("MenuBar.background", new ColorUIResource(Color.BLUE));

        pack();
        setLocationRelativeTo(null);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        loginButton.addActionListener(this);
        clearButton.addActionListener(this);
        exitButton.addActionListener(this);

        UserList = new ArrayList<>();
        UserList.add(new User("Xolani", "073471"));
        UserList.add(new User("Masimbe", "67678"));
    }

    @Override
    public void actionPerformed(ActionEvent e) {
        if (e.getSource() == loginButton || e.getActionCommand().equals("Login")) {
            String username = usernameField.getText();
            String password = new String(passwordField.getPassword());
            boolean found = false;
            for (User user : UserList) {
                if (user.getUsername().equals(username) && user.getPassword().equals(password)) {
                    found = true;
                    break;
                }
            }
            if (found) {
                JOptionPane.showMessageDialog(null, "Login Correct");
            } else {
                JOptionPane.showMessageDialog(null, "Invalid Username or Password");
            }

        } else if (e.getSource() == clearButton || e.getActionCommand().equals("Clear")) {
            usernameField.setText("");
            passwordField.setText("");

        } else if (e.getSource() == exitButton || e.getActionCommand().equals("Exit")) {
            System.exit(0);
        }
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> new UserLoginGui().setVisible(true));
    }
}
